<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'Litmaps';
require_once('./config.php');
check_login();
require_once('inc/topBarNav.php');
require_once('inc/header.php');

// Database connection
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "otas_db"; 

$db = new mysqli($host, $username, $password, $database);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if the user is a student
$student_id = $_SESSION['student_id'] ?? null;

if (!$student_id) {
    die("User not logged in as student.");
}

// Query `recent_student_mappings` for the logged-in student
$recentMappingsQuery = "SELECT rm.mapping_id 
                        FROM recent_student_mappings rm 
                        WHERE rm.student_id = ?";
$stmt = $db->prepare($recentMappingsQuery);
$stmt->bind_param("i", $student_id);

// Execute the query and fetch recent mappings
$stmt->execute();
$stmt->bind_result($mappingId);
$recentMappings = [];
while ($stmt->fetch()) {
    $recentMappings[] = $mappingId;
}
$stmt->close();

// Fetch details for each mapping in `$recentMappings`, include all authors and abstract
$relatedLiteratureQuery = "
SELECT al.id, 
       COALESCE(al.title, 'Unknown Title') AS title, 
       COALESCE(al.year, 'Unknown Year') AS year, 
       COALESCE(al.abstract, 'No Abstract Available') AS abstract,
       (SELECT CONCAT(COALESCE(aa.first_name, ''), ' ', COALESCE(aa.last_name, '')) 
        FROM archive_authors aa 
        WHERE aa.archive_id = al.id 
        AND aa.author_order = 1 LIMIT 1) AS primary_author,
       GROUP_CONCAT(CONCAT(COALESCE(aa.first_name, ''), ' ', COALESCE(aa.last_name, '')) SEPARATOR ', ') AS authors,
       -- Add Citation Count
       (SELECT COUNT(*) 
        FROM citation_relationships cr 
        WHERE cr.cited_paper_id = al.id) AS citation_count
FROM archive_list al
LEFT JOIN archive_authors aa ON al.id = aa.archive_id
WHERE al.status = 1 
AND al.id IN (
    SELECT rm.mapping_id FROM recent_student_mappings rm WHERE rm.student_id = ?
    UNION
    SELECT cr.cited_paper_id FROM citation_relationships cr
    WHERE cr.citing_paper_id IN (
        SELECT rm.mapping_id FROM recent_student_mappings rm WHERE rm.student_id = ?
    )
    UNION
    SELECT cr.citing_paper_id FROM citation_relationships cr
    WHERE cr.cited_paper_id IN (
        SELECT rm.mapping_id FROM recent_student_mappings rm WHERE rm.student_id = ?
    )
)
GROUP BY al.id";



// Prepare and execute the query
$stmt = $db->prepare($relatedLiteratureQuery);
$stmt->bind_param("iii", $student_id, $student_id, $student_id);
$stmt->execute();
$stmt->bind_result($id, $title, $year, $abstract, $primary_author, $authors, $citation_count);

// Fetch and store the data
$literatureData = [];
while ($stmt->fetch()) {
    $literatureData[] = [
        'id' => $id,
        'title' => $title,
        'year' => $year,
        'abstract' => $abstract,
        'primary_author' => $primary_author,
        'authors' => $authors,
        'citation_count' => $citation_count
    ];
}
$stmt->close();

// Fetch all citations (both citing and cited relationships)
$citationQuery = "
    SELECT cr.citing_paper_id, cr.cited_paper_id
    FROM citation_relationships cr
    WHERE cr.citing_paper_id IN (
        SELECT rm.mapping_id FROM recent_student_mappings rm WHERE rm.student_id = ?
    )
    OR cr.cited_paper_id IN (
        SELECT rm.mapping_id FROM recent_student_mappings rm WHERE rm.student_id = ?
    )
";
$stmt = $db->prepare($citationQuery);
$stmt->bind_param("ii", $student_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();
$citations = [];
while ($row = $result->fetch_assoc()) {
    $citations[] = $row;
}
$stmt->close();

// Prepare data for frontend
$response = [
    "literature" => $literatureData,
    "citations" => $citations
];

?>

<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUPSRC LitTrack</title>
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://d3js.org/d3.v6.min.js"></script>
    <style>
     /* Main content wrapper, responsive to sidebar state */
     body {
    font-family: var(--bs-body-font-family);
    font-size: var(--bs-body-font-size);
    font-weight: var(--bs-body-font-weight);
    line-height: var(--bs-body-line-height);
    color: var(--bs-body-color);
    text-align: var(--bs-body-text-align);
    background-color: var(--bs-body-bg);
}
     .content-wrapper {
        transform: translateX(-13%); /* Moves the container up by 10% of its height */
margin-top: -10px;
    align-items: center;
        transition: margin-left 0.3s ease-in-out;
        background-color: #ffffff; /* Set background color to white */
        padding: 60px; /* Add padding for a cleaner look */
    }

    .search-container {
    width: 80%;
    max-width: 600px;
    margin: 50px auto 20px;
    text-align: center;
    position: relative; /* Set position relative to allow .search-results to position absolutely */
}

#searchBar {
    width: 100%;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.search-results {
    position: absolute; /* Position the search results absolutely */
    top: 100%; /* Position below the search bar */
    left: 0; /* Align to the left edge of the search container */
    width: 100%; /* Match the width of the search container */
    z-index: 100; /* Ensure it overlaps other content */
    background-color: white; /* Background color for visibility */
    border: 1px solid #ddd; /* Optional border */
    border-radius: 8px;
    max-height: 300px; /* Optional: Set max height for scrolling */
    overflow-y: auto;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow for better aesthetics */
}

.result-item {
    padding: 10px;
    cursor: pointer;
    text-align: left;
}

.result-item:hover {
    background-color: #f0f0f0;
}


   /* Map container */
/* Map container (Litmapping section) */
#map {
    width: 100%; /* Increased width for a larger display */
    height: 600px; /* Adjusted height for better visibility */
    margin: 20px auto; /* Center the map with some spacing */
    border: 1px solid #ddd; /* Retain border for structure */
    border-radius: 10px; /* Rounded corners for aesthetics */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow for depth */
}

    /* Tooltip */
    .tooltip {
        position: absolute;
        text-align: left;
        max-width: 300px;
        padding: 8px;
        font: 14px sans-serif;
        background: lightsteelblue;
        border-radius: 8px;
        pointer-events: none;
        color: #000;
        line-height: 1.4;
    }
        /* Sidebar styling */
.sidebar {
    overflow: hidden; /* Hide scrollbar */
    /* other styling like width, height, background color, etc. */
}
        /* html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; 
        } */
        .main-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            transition: width 0.3s ease-in-out; 
            overflow-y: auto; 
            overflow-x: hidden; 
            background-color: white;
        }
        .main-sidebar::-webkit-scrollbar {
            display: none;
        }
        .main-sidebar {
            -ms-overflow-style: none; 
            scrollbar-width: none; 
        }
        body.sidebar-collapsed .main-sidebar {
            width: 70px;
        }
        .main-sidebar .nav-link p {
            display: inline;
        }
        body.sidebar-collapsed .main-sidebar .nav-link p {
            display: none;
        }
        .main-sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 10px;
        }
        body.sidebar-collapsed .main-sidebar .nav-link i {
            text-align: center;
            margin-right: 0;
            width: 100%;
        }
        .content-wrapper {
    margin-left: 250px; /* Align with the sidebar */
    margin-right: 20px; /* Add spacing on the right side */
    transition: margin-left 0.3s ease-in-out;
    padding: 60px 20px; /* Maintain padding for a balanced layout */
    height: auto; /* Ensure it adjusts with the content */
}
        body.sidebar-collapsed .content-wrapper {
            margin-left: 60px;
        }
        .brand-link {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            transition: padding 0.3s ease;
            height: 3.5rem;
            overflow: hidden;
        }
        .brand-link .brand-image {
            width: 2.5rem;
            height: 2.5rem;
            transition: width 0.3s ease, height 0.3s ease;
            margin-right: 0.5rem;
        }
        body.sidebar-collapsed .brand-link .brand-image {
            width: 2rem;
            height: 2rem;
            margin-right: 0; 
        }
        .brand-link .brand-text {
            font-size: 1rem;
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }
        body.sidebar-collapsed .brand-link .brand-text {
            opacity: 0;
            overflow: hidden;
        }
        /* Position the control buttons outside of the map */
.control-buttons {
    position: fixed; /* Attach to the viewport */
    right: 20px; /* Distance from the right edge of the screen */
    bottom: 150px; /* Distance from the bottom of the screen */
    display: flex; /* Stack buttons vertically */
    flex-direction: column;
    gap: 10px; /* Spacing between buttons */
    z-index: 1000; /* Ensure they are above other elements */
    transform: translateX(150%); /* Moves the container up by 10% of its height */
}

/* Styling for buttons */
.control-button {
    font-size: 16px;
    width: 40px; /* Adjust width */
    height: 40px; /* Adjust height */
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px; /* Rounded corners */
    cursor: pointer;
}

/* Hover effects */
.control-button:hover {
    background-color: #f0f0f0;
}
#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    display: none;
}

#studyModal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    z-index: 1000;
    padding: 20px;
    border-radius: 8px;
    display: none;
}

.modal-content {
    position: relative;
}

/* Modal styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    overflow-y: auto;
}

.modal-content {
    background-color: #fefefe;
    margin: auto;
    top: 50%;
    transform: translate(0%, -50%);
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 1200px;
    border-radius: 8px;
    overflow-y: auto;
    position: relative;
    max-height: calc(100vh - 100px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    cursor: pointer;
}

.close:hover {
    color: red;
}

.citation-style {
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
    font-size: 14px;
}

.citation-style strong {
    flex-basis: 30%;
    text-align: left;
}

.citation-style span {
    flex-basis: 100%;
    text-align: start;
}

/* Close button styling */
.close {
    position: absolute; /* Positioned within the modal */
    top: 10px;
    right: 10px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    cursor: pointer;
}

.close:hover {
    color: red; /* Red highlight on hover */
}

    .citation-style {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
        font-size: 14px;
    }

    .citation-style strong {
        flex-basis: 30%; /* Adjusts the width of the label column */
        text-align: left;
    }

    .citation-style span {
        flex-basis: 100%; /* Adjusts the width of the value column */
        text-align: start;
    }

    </style>
</head>
<body>

<div class="content-wrapper">
    <div class="search-container">
        <input type="text" id="searchBar" placeholder=" Search by title, keyword, author, or publication year.." autocomplete="off">
        <div class="search-results" id="searchResults"></div>
    </div>

    <div id="map"></div>
        <!-- Control Buttons -->
<!-- Control Buttons -->
<div class="control-buttons">
    <button class="control-button top-left" id="togglePan">✦</button>
    <button class="control-button top-right" id="recenterMap">↻</button>
    <button class="control-button bottom-left" id="zoomIn">+</button>
    <button class="control-button bottom-right" id="zoomOut">−</button>
</div>

<!-- Modal Structure -->
<div id="studyModal" class="modal" style="display:none; position:fixed; z-index:1000; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fff; margin:0 auto; padding:20px; border-radius:8px; width:100%; max-width:1000px; position:relative; top:50%; left:45%; transform:translate(-50%, -50%); box-shadow:0px 4px 8px rgba(0,0,0,0.2);">
        <span id="closeModal" style="position:absolute; top:10px; right:10px; font-size:24px; font-weight:bold; cursor:pointer;">&times;</span>
        <h2 id="modalTitle" style="text-align:center;"></h2>
        <p><strong>Authors:</strong> <span id="modalAuthors"></span></p>
        <p><strong>Year:</strong> <span id="modalYear"></span></p>
        <p><strong>Citations:</strong> <span id="modalCitations"></span></p>
        <p><strong>Abstract:</strong> <span id="modalAbstract"></span></p>
    </div>
</div>




<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const mappingId = parseInt(urlParams.get('id')); // Get and parse the ID

    console.log("Mapping ID from URL:", mappingId); // Debug

    if (mappingId) {
        loadMapping(mappingId);
    }
});

// JavaScript functions for search, mapping, and visualization
function performSearch(query) {
    query = query.trim();
    let resultsDiv = document.getElementById('searchResults');

    if (query.length === 0) {
        resultsDiv.innerHTML = '';
        resultsDiv.style.display = 'none';
        return;
    } else {
        resultsDiv.style.display = 'block';
    }

    fetch(`search_studies.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            resultsDiv.innerHTML = '';
            data.forEach(study => {
                let div = document.createElement('div');
                div.classList.add('result-item');
                div.innerHTML = `<strong>${study.author} (${study.publication_year})</strong><br>${study.title}`;
                
                div.onclick = () => {
                    loadMapping(study.id); // Load the selected study mapping
                    fetch(`saveMapping.php?id=${study.id}`) // Save the mapping
                        .then(response => response.text())
                        .then(data => {
                            console.log("Save Mapping Response:", data);
                        })
                        .catch(error => {
                            console.error("Error saving mapping:", error);
                        });

                    // Clear and hide the search results
                    resultsDiv.innerHTML = '';
                    resultsDiv.style.display = 'none';
                };

                resultsDiv.appendChild(div);
            });
        })
        .catch(error => {
            console.error("Error fetching search results:", error);
            resultsDiv.innerHTML = '';
        });
}

document.getElementById('searchBar').addEventListener('input', function() {
    performSearch(this.value);
});

async function loadMapping(studyId) {
    try {
        const response = await fetch(`get_mapping_data.php?study_id=${studyId}`);
        const data = await response.json();

        if (!data || !data.literature || !data.citations) {
            console.error("Invalid data structure:", data);
            return;
        }

        const citationNodeIds = new Set(data.citations.flatMap(citation => [citation.citing_paper_id, citation.cited_paper_id]));
        const literatureNodeIds = new Set(data.literature.map(node => node.id));

        // Fetch missing nodes if necessary
        for (const id of citationNodeIds) {
            if (!literatureNodeIds.has(id)) {
                const nodeResponse = await fetch(`get_mapping_data.php?study_id=${id}`);
                const nodeData = await nodeResponse.json();
                if (nodeData.literature.length > 0) {
                    data.literature.push(nodeData.literature[0]);
                }
            }
        }

        console.log("Loaded Data:", data); // Debug

        // Pass the studyId to highlight it in the visualization
        updateD3Visualization(data, studyId);
    } catch (error) {
        console.error("Error loading mapping data:", error);
    }
}

// Modal and zoom control logic
document.getElementById("closeModal").onclick = () => {
    document.getElementById("studyModal").style.display = "none";
};

window.onclick = event => {
    const modal = document.getElementById("studyModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

function updateD3Visualization(data, selectedStudyId) {
    const width = document.getElementById("map").clientWidth;
    const height = document.getElementById("map").clientHeight;

    // Clear the existing SVG for updates
    d3.select("#map").select("svg").remove();

    // Create SVG element
    const svg = d3.select("#map").append("svg")
        .attr("width", width)
        .attr("height", height);

    // Add zoom behavior
    const zoom = d3.zoom()
        .scaleExtent([0.5, 5])  // Control min/max zoom levels
        .on("zoom", function(event) {
            svg.select("g").attr("transform", event.transform);  // Apply zoom and pan transformations
        });

    svg.call(zoom);  // Activate zoom behavior on the SVG

    const g = svg.append("g"); // Grouping element to hold nodes and links

    // Create tooltip for hover
    const tooltip = d3.select("body").append("div")
        .attr("class", "tooltip")
        .style("opacity", 0);

    const formattedCitations = data.citations.map(citation => ({
        source: citation.citing_paper_id,
        target: citation.cited_paper_id
    }));

    // Draw links
    const link = g.selectAll(".link")
        .data(formattedCitations)
        .enter().append("line")
        .attr("class", "link")
        .style("stroke", "#999")
        .style("stroke-width", 1.5);

    // Draw nodes
    const node = g.selectAll(".node")
        .data(data.literature)
        .enter().append("g")
        .attr("class", "node")
        .on("mouseover", (event, d) => {
            // Show tooltip on hover
            tooltip.transition()
                .duration(200)
                .style("opacity", 0.9);
            tooltip.html(` 
                ${d.authors || "Unknown Author"}
                (${d.year || d.publication_year || "Unknown Year"})<br>
                ${d.title || "No Title"}
            `)
                .style("left", (event.pageX + 10) + "px")
                .style("top", (event.pageY - 10) + "px");
        })
        .on("mouseout", () => {
            // Hide tooltip
            tooltip.transition()
                .duration(500)
                .style("opacity", 0);
        })
        .on("click", (event, d) => {
            // Hide tooltip explicitly when clicking the node
            tooltip.style("opacity", 0);

            // Populate the modal with study details
            document.getElementById("modalTitle").innerText = d.title || "No Title Available";
            document.getElementById("modalAuthors").innerText = d.authors || "Unknown Author(s)";
            document.getElementById("modalAbstract").innerText = d.abstract || "No Abstract Available";
            document.getElementById("modalYear").innerText = d.year || d.publication_year || "Unknown Year";
            document.getElementById('modalCitations').textContent = d.citation_count; // Display citation count
            
            // Show the modal
            const modal = document.getElementById("studyModal");
            modal.style.display = "block";
        });

// Append circles with conditional shading and size based on citation count
node.append("circle")
    .attr("r", d => 8 + Math.min(d.citation_count * 5, 100)) // Make the node size grow faster with citation count
    .attr("fill", d => d.id === selectedStudyId ? "gray" : "white") // Highlight only the selected node
    .attr("stroke", "black") // Stroke for all nodes
    .attr("stroke-width", 1.5);


    // Add labels for the nodes
    node.append("text")
        .attr("x", 12)
        .attr("dy", ".35em")
        .text(d => `${d.primary_author || "Unknown Primary Author"} (${d.year || d.publication_year || "Unknown Year"})`);

    const simulation = d3.forceSimulation(data.literature)
        .force("link", d3.forceLink(formattedCitations).id(d => d.id).distance(200))
        .force("charge", d3.forceManyBody().strength(-500))
        .force("center", d3.forceCenter(width / 2, height / 2));

    simulation.on("tick", () => {
        link.attr("x1", d => d.source.x)
            .attr("y1", d => d.source.y)
            .attr("x2", d => d.target.x)
            .attr("y2", d => d.target.y);

        node.attr("transform", d => `translate(${d.x},${d.y})`);
    });

    // Add recenter button functionality
    const recenterButton = document.getElementById("recenterMap");
    if (recenterButton) {
        recenterButton.addEventListener('click', () => {
            zoom.transform(svg, d3.zoomIdentity);  // Reset zoom and pan to original position
        });
    }

    // Add zoom in button functionality
    const zoomInButton = document.getElementById("zoomIn");
    zoomInButton.addEventListener('click', () => {
        zoom.scaleBy(svg, 1.2); // Zoom in by 20%
    });

    // Add zoom out button functionality
    const zoomOutButton = document.getElementById("zoomOut");
    zoomOutButton.addEventListener('click', () => {
        zoom.scaleBy(svg, 0.8); // Zoom out by 20%
    });
}

// Toggle pan functionality and cursor change
let isPanning = false;
document.getElementById("togglePan").addEventListener("click", () => {
    isPanning = !isPanning;
    const mapContainer = document.getElementById("map");

    if (isPanning) {
        // Enable panning (hand cursor)
        mapContainer.style.cursor = "grab";
        d3.select("svg").style("pointer-events", "all"); // Allow pan interaction
    } else {
        // Disable panning (pointer cursor)
        mapContainer.style.cursor = "pointer";
        d3.select("svg").style("pointer-events", "none"); // Disable pan interaction
    }
});
</script>


</body>
</html>