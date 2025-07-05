
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calamity Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.28"></script>
  <style>
    .status-active { color: red; }
    .status-recovery { color: orange; }
    .status-resolved { color: green; }
  </style>
</head>
<body>
  <?php 
  include 'sidebar.php';
  ?>
<main class="main container" id="main">
  <h3>Calamity Data Management</h3>
  <div class="card p-4">
    <form id="calamityForm">
      <div class="row mb-3">
        <div class="col-md-3">
          <label for="calamityType" class="form-label">Calamity Type</label>
          <select id="calamityType" name="calamityType" class="form-select" required onchange="toggleSeverityLevel()">
            <option value="Flood">Flood</option>
            <option value="Typhoon">Typhoon</option>
            <option value="Earthquake">Earthquake</option>
            <option value="Fire">Fire</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="calamityName" class="form-label">Calamity Name/ID</label>
          <input type="text" id="calamityName" name="calamityName" class="form-control" required>
        </div>
        <div class="col-md-3" id="severityLevelContainer">
          <label for="intensityLevel" class="form-label">Intensity Level</label>
          <select id="intensityLevel" name="intensityLevel" class="form-select">
            <option>Severe Tropical Storm</option>
            <option>Super Typhoon</option>
            <option>High Intensity</option>
            <option>Moderate</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="date" class="form-label">Date</label>
          <input type="date" id="date" name="date" class="form-control" required>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-3" id="floodRiskLevelContainer">
          <label for="floodRiskLevel" class="form-label">Flood Risk Level</label>
          <select id="floodRiskLevel" name="floodRiskLevel" class="form-select">
            <option>Medium</option>
            <option>High</option>
            <option>Low</option>
          </select>
        </div>
        <div class="col-md-3" id="fireSeverityContainer">
          <label for="fireSeverityLevel" class="form-label">Fire Severity Level</label>
          <select id="fireSeverityLevel" name="fireSeverityLevel" class="form-select">
            <option>Low</option>
            <option>Moderate</option>
            <option>High</option>
            <option>Extreme</option>
          </select>
        </div>
        <div class="col-md-3" id="earthquakeMagnitudeContainer">
          <label for="magnitudeLevel" class="form-label">Magnitude Level</label>
          <select id="magnitudeLevel" name="magnitudeLevel" class="form-select">
            <option>Minor (2.5 or less)</option>
            <option>Light (2.5 to 5.4)</option>
            <option>Moderate (5.5 to 6.0)</option>
            <option>Strong (6.1 to 6.9)</option>
            <option>Major (7.0 to 7.9)</option>
            <option>Great (8.0 or greater)</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="status" class="form-label">Status</label>
          <select id="status" name="status" class="form-select">
            <option value="Active">Active</option>
            <option value="Recovery">Recovery</option>
            <option value="Resolved">Resolved</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-success">Submit</button>
    </form>
  </div>

  <h4 class="mt-5">Calamity History</h4>
  <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search calamities" class="form-control mb-3">
  <table id="historyTable" class="table table-bordered">
    <thead>
      <tr>
        <th>Type</th>
        <th>Name</th>
        <th>Date</th>
        <th>Intensity</th>
        <th>Flood Risk</th>
        <th>Fire Severity</th>
        <th>Magnitude</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($calamities)): ?>
        <?php foreach ($calamities as $calamity): ?>
          <tr data-id="<?php echo $calamity['id']; ?>
            <td><?php echo $calamity['calamityType']; ?></td>
            <td><?php echo $calamity['calamityName']; ?></td>
            <td><?php echo date("F j, Y", strtotime($calamity['date'])); ?></td>
            <td><?php echo $calamity['calamityType'] == 'Typhoon' ? $calamity['intensityLevel'] : ''; ?></td>
            <td><?php echo $calamity['calamityType'] == 'Flood' ? $calamity['floodRiskLevel'] : ''; ?></td>
            <td><?php echo $calamity['calamityType'] == 'Fire' ? $calamity['fireSeverityLevel'] : ''; ?></td>
            <td><?php echo $calamity['calamityType'] == 'Earthquake' ? $calamity['magnitudeLevel'] : ''; ?></td>
            <td class="status-<?php echo strtolower($calamity['status']); ?>"><?php echo $calamity['status']; ?></td>
            <td>
            <button class="btn btn-sm btn-success edit-status-btn">Edit</button>
          </td>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="9" class="text-center">No calamities found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  <button class="btn btn-success" onclick="redirectToTable();">
   View Table
</button>
</main>

<script>

function redirectToTable() {
    window.location.href = 'calamityData.php';
  }
  // Toggle severity level visibility
  function toggleSeverityLevel() {
    const type = document.getElementById("calamityType").value;
    document.getElementById("intensityLevel").parentElement.style.display = type === "Typhoon" ? "block" : "none";
    document.getElementById("floodRiskLevelContainer").style.display = type === "Flood" ? "block" : "none";
    document.getElementById("fireSeverityContainer").style.display = type === "Fire" ? "block" : "none";
    document.getElementById("earthquakeMagnitudeContainer").style.display = type === "Earthquake" ? "block" : "none";
  }

  function searchTable() {
      const input = document.getElementById("searchInput").value.toUpperCase();
      const rows = document.querySelectorAll("#historyTable tbody tr");

      rows.forEach(row => {
        const text = row.innerText.toUpperCase();
        row.style.display = text.includes(input) ? "" : "none";
      });
    }
    function redirectToTable() {
    window.location.href = 'calamityData.php';
  }
  $(document).on("click", ".edit-status-btn", function () {
      const row = $(this).closest("tr");
      const calamityId = row.data("id");
      const currentStatus = row.find("span").text();

      const statusDropdown = `
        <select class="form-select form-select-sm calamity-status-dropdown">
          <option value="Active" ${currentStatus === "Active" ? "selected" : ""}>Active</option>
          <option value="Recovery" ${currentStatus === "Recovery" ? "selected" : ""}>Recovery</option>
          <option value="Resolved" ${currentStatus === "Resolved" ? "selected" : ""}>Resolved</option>
        </select>
      `;

      row.find("td:nth-child(😎").html(statusDropdown);

      $(this)
        .removeClass("edit-status-btn btn-success")
        .addClass("save-status-btn btn-danger")
        .text("Save");
    });

    $(document).on("click", ".save-status-btn", function () {
      const row = $(this).closest("tr");
      const calamityId = row.data("id");
      const newStatus = row.find(".calamity-status-dropdown").val();

      $.ajax({
        url: "",
        type: "POST",
        data: {
          id: calamityId,
          status: newStatus,
          action: "update_status"
        },
        dataType: "json",
        success: function (response) {
          alert(response.message);
          if (response.success) {
            row
              .find("td:nth-child(😎")
              .html(
                <span class="status-${newStatus.toLowerCase()}">${newStatus}</span>
              );
            row
              .find(".save-status-btn")
              .removeClass("save-status-btn btn-danger")
              .addClass("edit-status-btn btn-success")
              .text("Edit");
          }
        },
        error: function () {
          alert("An unexpected error occurred.");
        }
      });
    });

  // Form submission with AJAX
  $("#calamityForm").submit(function (e) {
    e.preventDefault();
    const formData = $(this).serializeArray();
    formData.push({ name: "action", value: "save_calamity" });

    $.post("", formData, function (response) {
      Swal.fire(response.success ? "Success" : "Error", response.message, response.success ? "success" : "error")
        .then(() => location.reload());
    }, "json");
  });

  // Update status
  function updateStatus(id, status) {
    $.post("", { action: "update_status", id, status }, function (response) {
      Swal.fire(response.success ? "Success" : "Error", response.message, response.success ? "success" : "error")
        .then(() => location.reload());
    }, "json");
  }

  // Initialize visibility on page load
  toggleSeverityLevel();
</script>
</body>
</html>