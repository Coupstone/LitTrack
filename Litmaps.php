<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'Litmaps';
require_once('./config.php'); 
require_once('inc/topBarNav.php'); 
require_once('inc/header.php'); 

// Database connection
$host = "localhost"; // Use your DB host
$username = "root"; // Your DB username
$password = ""; // Your DB password
$database = "otas_db"; // Your database name

$db = new mysqli($host, $username, $password, $database);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Query to fetch literature details with only the primary author (lowest author_order)
$query = "
    SELECT al.id, al.title, al.year AS publication_year, CONCAT(aa.first_name, ' ', aa.last_name) AS author
    FROM archive_list al
    LEFT JOIN archive_authors aa ON al.id = aa.archive_id
    WHERE al.status = 1 AND aa.author_order = 1
";
$literature_result = $db->query($query);

if(!$literature_result) {
    die("Error fetching literature: " . $db->error);
}

$literature = [];
while ($row = $literature_result->fetch_assoc()) {
    $literature[] = $row;
}

// Fetch citation relationships from the citation_relationships table
$citation_query = "SELECT citing_paper_id, cited_paper_id FROM citation_relationships";
$citation_result = $db->query($citation_query);

if(!$citation_result) {
    die("Error fetching citations: " . $db->error);
}

$citations = [];
while ($row = $citation_result->fetch_assoc()) {
    $citations[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <title>Literature Map</title>
    <!-- Load D3.js -->
    <script src="https://d3js.org/d3.v6.min.js"></script>
    <style>
        #map {
            width: 100vw;
            height: 100vh;
        }
        .node circle {
            stroke: #fff;
            stroke-width: 1.5px;
        }
        .node text {
            font: 12px sans-serif;
            pointer-events: none;
        }
        .link {
            stroke: #999;
            stroke-opacity: 0.6;
        }
        .tooltip {
    position: absolute;
    text-align: left; /* Align text to the left */
    max-width: 300px; /* Increase the maximum width */
    padding: 8px; /* Add more padding */
    font: 14px sans-serif; /* Increase font size for readability */
    background: lightsteelblue;
    border: 0;
    border-radius: 8px;
    pointer-events: none;
    white-space: normal; /* Allow text to wrap */
    color: #000; /* Optional: improve text contrast */
    line-height: 1.4; /* Optional: add line spacing for readability */
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

<h1 class="text-center">Literature Mapping</h1>
<div id="map"></div>

<script>
// Sample data from PHP
const literature = <?php echo json_encode($literature); ?>;
const citations = <?php echo json_encode($citations); ?>;

console.log("Literature Data:", literature); // Debugging: print literature data
console.log("Citation Data:", citations); // Debugging: print citation data

// Convert citations to D3 format
const citationLinks = citations.map(citation => ({
    source: citation.citing_paper_id,
    target: citation.cited_paper_id
}));

const width = window.innerWidth;
const height = window.innerHeight;

// Create SVG
const svg = d3.select("#map").append("svg")
              .attr("width", width)
              .attr("height", height)
              .call(d3.zoom().on("zoom", (event) => {
                  svg.attr("transform", event.transform); // Apply zoom transform
              }))
              .append("g"); // Append the group element after applying zoom

// Tooltip
const tooltip = d3.select("body").append("div")
                  .attr("class", "tooltip")
                  .style("opacity", 0);

// Simulation setup
const simulation = d3.forceSimulation(literature)
                     .force("link", d3.forceLink(citationLinks).id(d => d.id).distance(200))
                     .force("charge", d3.forceManyBody().strength(-500))
                     .force("center", d3.forceCenter(width / 2, height / 2));

// Function to draw a curved link between two nodes using a quadratic Bézier curve
function curvedLinkPath(d) {
    const midX = (d.source.x + d.target.x) / 2;
    const midY = (d.source.y + d.target.y) / 2;
    const dx = d.target.x - d.source.x;
    const dy = d.target.y - d.source.y;
    const curveOffset = Math.sqrt(dx * dx + dy * dy) / 4; // Control the curvature
    return `M ${d.source.x},${d.source.y} Q ${midX + curveOffset},${midY - curveOffset} ${d.target.x},${d.target.y}`;
}

// Links (as paths instead of lines)
const link = svg.append("g")
                .attr("class", "links")
                .selectAll("path")
                .data(citationLinks)
                .enter().append("path")
                .attr("class", "link")
                .attr("stroke-width", 1.5)
                .attr("stroke", "#999")
                .attr("fill", "none")
                .attr("stroke-opacity", 0.6);

// Nodes
const node = svg.append("g")
                .attr("class", "nodes")
                .selectAll("g")
                .data(literature)
                .enter().append("g")
                .attr("class", "node")
                .on("mouseover", function(event, d) {
                    link.attr("stroke", l => (l.source.id === d.id || l.target.id === d.id) ? "#00f" : "#ccc")
                        .attr("stroke-opacity", l => (l.source.id === d.id || l.target.id === d.id) ? 1 : 0.1);

                    node.selectAll("circle")
                        .attr("fill", n => (citationLinks.some(l => (l.source.id === d.id && l.target.id === n.id) || (l.target.id === d.id && l.source.id === n.id))) ? "gray" : "#ccc")
                        .attr("opacity", n => (citationLinks.some(l => (l.source.id === d.id && l.target.id === n.id) || (l.target.id === d.id && l.source.id === n.id))) ? 1 : 0.3);

                    tooltip.transition()
                        .duration(200)
                        .style("opacity", .9);
                    tooltip.html(`${d.author} (${d.publication_year}).<br>${d.title}`)
                        .style("left", Math.min(event.pageX + 10, window.innerWidth - 320) + "px")
                        .style("top", Math.min(event.pageY - 10, window.innerHeight - tooltip.node().clientHeight - 10) + "px");
                })
                .on("mouseout", function() {
                    // Reset link and node appearances
                    link.attr("stroke", "#999")
                        .attr("stroke-opacity", 0.6);

                    node.selectAll("circle")
                        .attr("fill", "gray")
                        .attr("opacity", 1);

                    tooltip.transition()
                           .duration(500)
                           .style("opacity", 0);
                });

// Add circles to represent the nodes
node.append("circle")
    .attr("r", 8)
    .attr("fill", "gray");

// Add default text labels for the nodes (only author and date)
node.append("text")
    .attr("x", 12)
    .attr("dy", ".35em")
    .text(d => `${d.author} (${d.publication_year})`);

// Update positions on tick (curved links)
simulation.on("tick", () => {
    link.attr("d", curvedLinkPath); // Update the curved path on each tick

    node.attr("transform", d => `translate(${d.x},${d.y})`);
});
</script>
</body>
</html>
