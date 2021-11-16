My Life
========================

<h3>Install</h3>
<ol>
<li>Copy .env.example to .env and fill in the fields.</li>
<li>Build the docker-compose image and up -d the image</li>
<li>Run composer install</li>
<li>Run the migrations</li>
<li>Register the user in the route /register.</li>
<li>Optional: run npm install (not in container but with a global node install). 
Then link the UglifyJS and SCSS File Watchers to the node_modules/.bin binaries. 
The SCSS arguments is: --style compressed $FileParentDir$/scss/$FileName$:$FileParentDir$/css/$FileNameWithoutExtension$.css</li>
<li>Optional: import the sql-files in src/WineBundle/Data and copy the images to 
public/img/labels.</li>
</ol>

<h3>Testing</h3>

<ol>
<li>Run unitTests.sh to unit test.</li>
<li>Run featureTests.sh to feature test.</li>
</ol>
