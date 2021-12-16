My Life
========================

<h3>Install Development</h3>
<ol>
<li>Copy .env.local.example to .env.local and fill in the fields.
The DB_HOST has to be host.docker.internal and 
MAILER_DSN has to be smtp://name:password@domain.com:25</li>
<li>Build the docker-compose image and up -d the image.</li>
<li>Create the database and the test database. 
The test database has to be the database with suffix _test.</li>
<li>Run composer install.</li>
<li>Run the migrations.</li>
<li>Register the user in the route /register.</li>
<li>Run make:admin to make the first user admin.</li>
<li>Run npm install. Then link the UglifyJS and SCSS File Watchers to the node_modules/.bin binaries. 
The SCSS Arguments in PhpStorm is '--style compressed $FileParentDir$/scss/$FileName$:$FileParentDir$/css/$FileNameWithoutExtension$.css'.</li>
<li>Optional: run php bin/console app:import to fill the database with 
initial data and have initial wine labels.</li>
</ol>

<h3>Testing</h3>

<ol>
<li>Run unitTests.sh to unit test.</li>
<li>Run functionalTests.sh to functional test.</li>
</ol>

<h3>Production/Staging</h3>

<ol>
<li>Copy .env.local.example to .env.local and fill in the fields.</li>
<li>Copy bin/dockerBuildAndUp.sh to project dir and replace {APP_ENV} with prod or staging.</li>
<li>Follow the steps in update.sh.</li>
<li>Register the user in the route /register.</li>
<li>Run make:admin to make the first user admin.</li>
<li>For staging: allow certain ips only (and the server ip) in the ./config/apache.conf file.</li>
<li>In production: install postfix, dovecot, opendkim, opendmarc, postsrsd and spamassassin.</li>
<li>In production: add the mail users yourdevuser@manageleisure.com, info@manageleisure.com, noreply@manageleisure.com, 
postmaster@manageleisure.com and root@manageleisure.com by going to the url /mail-user.</li>
<li>An update in production or staging can be retrieved with the command ’./update.sh’</li>
<li>To revert the update execute ’./rollback.sh’.</li>
</ol>
