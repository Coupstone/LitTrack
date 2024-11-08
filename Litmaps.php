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

// Fetch initial literature data for recent Litmaps view
$query = "SELECT al.id, al.title, al.year AS publication_year, CONCAT(aa.first_name, ' ', aa.last_name) AS author 
          FROM archive_list al 
          LEFT JOIN archive_authors aa ON al.id = aa.archive_id 
          WHERE al.status = 1 AND aa.author_order = 1";
$literature_result = $db->query($query);

$literature = [];
while ($row = $literature_result->fetch_assoc()) {
    $literature[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <title>Literature Map</title>
    <!-- Load D3.js -->
    <script src="https://d3js.org/d3.v6.min.js"></script>
    
    
    <style>
         /* Center container for search bar and results */
        .search-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 320px; /* Increased width to provide a bit more space */
            background-color: white; /* Optional: add a background to the container */
            border: 1px solid #ccc; /* Optional: add a border to the container */
            padding: 8px; /* Optional: add padding to create some spacing */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: add shadow for better visibility */
            z-index: 1000; /* Make sure it stays above other elements */
            border-radius: 8px; /* Optional: rounded corners */
        }

        /* Style for search bar */
        #searchBar {
            width: 100%;
            padding: 8px;
            font-size: 16px;
        }

        /* Style for search results */
        #searchResults {
            width: 100%;
            background: #fff;
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            margin-top: 5px;
        }

        #map {
            width: 100vw;
            height: 100vh;
        }

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

        #searchResults div {
            padding: 8px;
            cursor: pointer;
        }
        #searchResults div:hover {
            background-color: #f0f0f0;
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

<div id="map"></div>

<!-- Centered Search Bar and Results -->
<div class="search-container">
    <div style="position: relative; width: 100%;">
        <input type="text" id="searchBar" placeholder="Type to search studies..." autocomplete="off">
        <button id="clearButton" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 16px; color: gray;">&times;</button>
    </div>
    <div id="searchResults"></div>
</div>


<script>
// JavaScript for autocomplete and D3.js visualization

// AJAX for real-time search suggestions
document.getElementById("searchBar").addEventListener("input", function() {
    let query = this.value;
    if (query.length > 0) { // Start searching from the first character
        fetch(`search_studies.php?query=${query}`)
        .then(response => response.json())
        .then(data => {
            let suggestions = document.getElementById("searchResults");
            suggestions.innerHTML = ""; // Clear previous suggestions
            data.forEach(study => {
                let option = document.createElement("div");
                // Display format: Author (Year) - Title
                option.innerHTML = `${study.author} (${study.publication_year}) - ${study.title}`;
                option.onclick = () => loadMapping(study.id);
                suggestions.appendChild(option);
            });
        });
    } else {
        document.getElementById("searchResults").innerHTML = ""; // Clear suggestions if query is empty
    }
});

// Clear button functionality
document.getElementById("clearButton").addEventListener("click", function() {
    document.getElementById("searchBar").value = ""; // Clear the search input
    document.getElementById("searchResults").innerHTML = ""; // Clear the search results
});


// Function to load mapping based on selected study ID
function loadMapping(studyId) {
    fetch(`get_mapping_data.php?study_id=${studyId}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById("searchResults").innerHTML = ""; // Clear search suggestions
        updateD3Visualization(data);
    });
}

// D3.js code to create and update visualization
function updateD3Visualization(data) {
    const width = window.innerWidth;
    const height = window.innerHeight;

    // Clear any existing SVG elements
    d3.select("#map").selectAll("svg").remove();

    const svg = d3.select("#map").append("svg")
        .attr("width", width)
        .attr("height", height)
        .call(d3.zoom().on("zoom", (event) => {
            svg.attr("transform", event.transform); // Apply zoom transform
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
