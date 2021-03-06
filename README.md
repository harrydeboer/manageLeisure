Manage Leisure
========================

<h3>Install Development</h3>
<ol>
<li>Copy .env.local.example to .env.local and fill in the fields.
The DB_HOST has to be host.docker.internal</li>
<li>Build the docker-compose image and up -d the image.</li>
<li>Create the database and the 4 test databases named ${DB_DATABASE}_test{1-4}.</li>
<li>Run composer install.</li>
<li>Run the migrations.</li>
<li>Register the user in the route /register.</li>
<li>Run make:admin to make the first user admin.</li>
<li>Run npm install (with a global node and npm). 
Then link the UglifyJS and SCSS File Watchers to the node_modules/.bin binaries. 
The SCSS Arguments in PhpStorm is 
'--style compressed $FileParentDir$/scss/$FileName$:$FileParentDir$/css/$FileNameWithoutExtension$.css'.</li>
<li>Run php bin/console app:import to fill the database with 
countries, regions, subregions, pages and grapes.</li>
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
<li>Run php bin/console app:import to fill the database with 
countries, regions, subregions, pages and grapes.</li>
<li>An update in production or staging can be retrieved with the command ’./update.sh’</li>
<li>To revert the update execute ’./rollback.sh’.</li>
</ol>
