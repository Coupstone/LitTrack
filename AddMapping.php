<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'Litmaps';
require_once('./config.php');
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

// Fetch details for each mapping in `$recentMappings`
$recentMappingData = [];
foreach ($recentMappings as $mappingId) {
    $query = "SELECT al.id, al.title, al.year, CONCAT(aa.first_name, ' ', aa.last_name) AS author 
              FROM archive_list al 
              LEFT JOIN archive_authors aa ON al.id = aa.archive_id 
              WHERE al.id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $mappingId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $recentMappingData[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <title>Add New Literature Mapping</title>
    <!-- Load D3.js -->
    <script src="https://d3js.org/d3.v6.min.js"></script>
    <style>
     /* Main content wrapper, responsive to sidebar state */
     .content-wrapper {
        margin-left: 250px; /* Default margin when sidebar is expanded */
        transition: margin-left 0.3s ease-in-out;
        background-color: #ffffff; /* Set background color to white */
        padding: 20px; /* Add padding for a cleaner look */
    }
    body.sidebar-collapsed .content-wrapper {
        margin-left: 70px; /* Adjust margin when sidebar is collapsed */
    }

    /* Search container */
    .search-container {
        width: 80%;
        max-width: 600px;
        margin: 50px auto 20px;
        text-align: center;
    }
    #searchBar {
        width: 100%;
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .search-results {
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        max-height: 300px;
        overflow-y: auto;
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
    #map {
        width: 80%;
        height: 500px;
        margin: 0 auto 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
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
html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; 
        }
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
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
            height: 100%;
            overflow: hidden;
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
    </style>
</head>
<body>

<div class="content-wrapper">
    <div class="search-container">
        <h2>Search Studies</h2>
        <input type="text" id="searchBar" placeholder="Type to search studies..." autocomplete="off">
        <div class="search-results" id="searchResults"></div>
    </div>

    <div id="map"></div>
</div>

<script>

document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const mappingId = urlParams.get('id');
        
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
                loadMapping(study.id);
                fetch(`saveMapping.php?id=${study.id}`)
                    .then(response => response.text())
                    .then(data => {
                        console.log("Save Mapping Response:", data);
                    })
                    .catch(error => {
                        console.error("Error saving mapping:", error);
                    });
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

function loadMapping(studyId) {
    fetch(`get_mapping_data.php?study_id=${studyId}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('searchResults').innerHTML = '';
        updateD3Visualization(data);
    });
}

function updateD3Visualization(data) {
    const width = document.getElementById("map").clientWidth;
    const height = document.getElementById("map").clientHeight;

    d3.select("#map").selectAll("svg").remove();

    const svg = d3.select("#map").append("svg")
        .attr("width", width)
        .attr("height", height)
        .call(d3.zoom().on("zoom", (event) => {
            svg.attr("transform", event.transform);
        }))
        .append("g");

    const tooltip = d3.select("body").append("div")
        .attr("class", "tooltip")
        .style("opacity", 0);

    const link = svg.selectAll(".link")
        .data(data.citations)
        .enter().append("line")
        .attr("class", "link")
        .style("stroke", "#999")
        .style("stroke-opacity", 0.6)
        .style("stroke-width", 1.5);

    const node = svg.selectAll(".node")
        .data(data.literature)
        .enter().append("g")
        .attr("class", "node")
        .on("mouseover", function(event, d) {
            tooltip.transition()
                .duration(200)
                .style("opacity", .9);
            tooltip.html(`${d.author} (${d.publication_year}).<br>${d.title}`)
                .style("left", (event.pageX + 10) + "px")
                .style("top", (event.pageY - 10) + "px");
        })
        .on("mouseout", function() {
            tooltip.transition()
                .duration(500)
                .style("opacity", 0);
        });

    node.append("circle")
        .attr("r", 8)
        .attr("fill", "gray");

    node.append("text")
        .attr("x", 12)
        .attr("dy", ".35em")
        .text(d => `${d.author} (${d.publication_year})`);

    const simulation = d3.forceSimulation(data.literature)
        .force("link", d3.forceLink(data.citations).id(d => d.id).distance(200))
        .force("charge", d3.forceManyBody().strength(-500))
        .force("center", d3.forceCenter(width / 2, height / 2));

    simulation.on("tick", () => {
        link.attr("x1", d => d.source.x)
            .attr("y1", d => d.source.y)
            .attr("x2", d => d.target.x)
            .attr("y2", d => d.target.y);

        node.attr("transform", d => `translate(${d.x},${d.y})`);
    });
}

</script>

</body>
</html>
