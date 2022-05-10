# Veeam RestAPI Simple Dashboard

**This repository contains the PHP source code for this simple web dashboard.**

This simple PHP website leverages the Veeam Backup & Replication (VBR) v11+ RestAPI to display a simple dashboard consisting of Veeam-managed servers, backup repositories, backup jobs and backups. 

## 📗 Documentation

**Author:** Ronn Martin (ronn.martin@veeam.com)

**System Requirements:** Veeam Backup & Replication RestAPI (v11+), PHP-enabled web server

**Operation:** Copy the files from the SourceCode folder to your PHP-enabled web server and follow your web server's procedure for creating a site.

**Usage:** Browse to the site created above. If operating properly an authentication prompt will be displayed -

![Dashboard Auth](images/VBR-PHP-Dashboard-Auth.png)

Enter the target VBR server address and admin credentials for the VBR instance.

If successfully authenticated a view similar to the one below will be displayed -

![Dashboard](images/VBR-PHP-Dashboard-Main.png)

## 🤝🏾 License

* [MIT License](LICENSE)