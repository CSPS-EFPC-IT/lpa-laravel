# Learning Product Application

The Learning Program Branch recently adopted an integrated governance and development framework known as the Learning Content Development Process. This process defines the roles, activities, deliverables and gates required to develop a new learning product from inception to evaluation phase.

The current version of the process is tailored for a course development project. It is expected that future releases of the process will eventually support the full suite of the school learning product types (ex.: videos, job aids, case studies).

The Learning Product Application (LPA) was designed to meet these requirements.

More information and documentation on the project can be found on [GCdocs].

## Implemented Processes
  - Project Approval
  - ~~Project Closure~~
  - Course Development
  - ~~Course Publication~~
  - ~~Course Deployment~~
  - ~~Course Modification~~
  - ~~Course Archival~~

## Technologies

LPA was designed using a single page application architecture.
The application uses a number of open source projects to work properly:
* [Laravel] - PHP Backend / Rest API
* [Camunda] - Process workflow engine
* [Vuejs] - Javascript framework
* [ElementUI] - Vuejs components library
* [Vagrant] - VM provisoning for local development

## Dependencies

To contribute to the development of this application, you must install these dependencies:
* PHP 7.2
* PHP Composer
* Nodejs
* Git
* VirutalBox
* Vagrant

## Installation

### Configure Laravel
Navigate to your **Laravel** application directory and copy the ***.env.example*** file to ***.env***.
Edit the ***.env*** file with the configuration values matching your local environment.

### Setup your dev-box virtual machine
You will need to download the dev-box VM in order to be able to deploy the application locally. You will find the dev-box under this [shared repository]. Create a **Vagrant** directory at the same level as your **Laravel** application directory. Within that **Vagrant** directory, create a subdirectory called **dev-box** and transfer these 3 files from the [shared repository]:
- dev.box
- Vagrantfile
- dev.box.sha384

Once the files are transferred, you can validate the integrity of the dev.box file using this command:
```bash
# On Linux:
cd Vagrant/dev-box
sha384sum --check dev.box.sha384
# Should output "dev.box: OK"

# On Windows:
# TBD
```

Install the NFS support to synchronize your local **Laravel** application directory with the one used by the dev-box VM.
```bash
# On Linux:
sudo apt-get install nfs-kernel-server

# On Windows:
vagrant plugin install vagrant-winnfsd
```

Update your **Vagrantfile** under the **Sync folder** configurations to match your OS.
Start the dev-box:
```bash
cd Vagrant/dev-box
vagrant up
```

### Run the installation command
Once you've got your local dev-box VM running, you will need to install the application.
```bash
cd Laravel
composer install
php artisan app:install --force --populate
php artisan app:build dev
```

You should now be able to access your LPA application at http://localhost:8000.

[GCDocs]: <http://gcdocs.csps-efpc.gc.ca/otcs/llisapi.dll?func=ll&objId=10205305>
[Laravel]: <https://laravel.com>
[Camunda]: <https://camunda.com>
[node.js]: <http://nodejs.org>
[Vuejs]: <https://vuejs.org>
[ElementUI]: <https://element.eleme.io/#/en-US>
[Vagrant]: <https://www.vagrantup.com>
[shared repository]: \\CSPS-NCA-V004\NCASHR002\SHAREDIR\IT\eschool\LPA
