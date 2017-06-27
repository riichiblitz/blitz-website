blitz-website - Riichi-blitz tournament entry point
===================


The website project, including PHP+MySQL backend with a JSON API, and HTML+JavaScript frontend.
It provides all the necessary functionality to announce, run and control a full-scale riichi tournament.
As simple as possible.

To deploy it on OpenShift you need to upload the sources on your PHP5 instance. You also need to enable MySQL for the instance and import the "blitz_dump.sql".
If something goes wrong, the problem could occures with unsetted environment variables. If so, delete the ".openshift/markers/hot_deploy" file. After commiting that change onto your OpenShift instance, it will be restarted, activating all the modules and updating environment variables. Neither if it solves your problem or not, revert the last commit.

The project is published under the terms of the WTFPL.