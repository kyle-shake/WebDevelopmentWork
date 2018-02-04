/*
File: proj3node.js

Author: Kyle Shake

Date Created: 3/2/2017

Last modified: 3/10/2017
	By: KS

SEE README FOR FURTHER FILE EXPLANATION

*/

var paul = require('/homes/paul/HTML/CS316/p3_req.js');


const exec = require('child_process').exec;
var http = require('http'),
    url = require('url'),
    fs = require('fs');


/*myprocess function
	Input: takes the request and response variables as parameters

  	Purpose:requests the url, parses the url, sends url to be validated
        if valid, sends url type to its respective function
        if not, sends error to console

*/
function myprocess(request, response){
    var aurl = request.url;
    var pathname = url.parse(aurl).pathname;
    var fileinfo = { directory: "", filetype: "", error: "" };
    var correctfile = okURL(pathname, fileinfo);
   
    if(correctfile){
        console.log("Request for "+pathname+" recieved!"); 
    
        if(fileinfo.filetype == "html"){
            console.log("Entering doHTML function.");
            doHTML(pathname, response);
        } else if (fileinfo.filetype == "cgi"){
            console.log("Entering doCGI function.");
            doCGI(pathname, response);
        } else if (fileinfo.filetype == "php"){
            console.log("Entering doPHP function.");
            doPHP(pathname, response);
        } else {
            console.log("Filetype did not match one of the three branches!");
        }
    }else{
        console.log("Request for "+pathname+" denied! "+fileinfo.error);
    }
}
/*okURL function
	Input: takes the URL and the object fileinfo as parameters

        Purpose: Splits the URL by the "." char
        Verifies file should only have two values in the array created by split function
	Checks the basename for non-alphanumeric characters
	Verifies the URL is a valid file type,
        if so, it assigns the type to the filetype variable inside the fileinfo object

*/
function okURL(pathname, fileinfo){
    console.log("Entering URL validation")
    
    //Initialize boolean variable that holds correct file status of incoming file
    var correctfile = true;

    //Check for extra periods and missing basenames (i.e. ".html")
    var splitarr = pathname.split(".");

    if (splitarr.length > 2){
        correctfile = false;
        fileinfo.error = "Too many periods!";
    }else if (splitarr[0] == ""){
        correctfile = false;
        fileinfo.error = "No basename!";
    }

    splitarr[0] = splitarr[0].slice(1);
    //Check for non-alphanumeric 
    if(/[^a-zA-Z0-9_]/.test(splitarr[0])){
        correctfile = false;
        fileinfo.error = "Basename contains non-alphanumeric characters or includes a directory!";
    } else { 
//    console.log("File has valid basename: "+splitarr[0]); //Used for Debug
    }

    //Check that pathname ends with correct filetype 
    //then assign respective filetype inside fileinfo obj
    if(pathname.endsWith(".cgi")){
        fileinfo.filetype = "cgi";
    }else if(pathname.endsWith(".html")){
        fileinfo.filetype = "html";
    }else if (pathname.endsWith(".php")){
        fileinfo.filetype = "php";
    }else{
        correctfile = false;
    }
    
    
    //Debug help    
    if(correctfile){
        console.log("File has valid type. It is a "+fileinfo.filetype+" file");
    } else {
        console.log("File has invalid type.");
    }
 
    return correctfile;
}


function doHTML(pathname, response){
    
    pathname = "MYHTML"+pathname;
    //attempt to readfile, verify that document exists
    //if not, send an error message to browser and server
    //if so, service the HTML file to browser
    fs.readFile(pathname, function(err, data){
        if (err){
            console.log(err);
	    //If readFile fails, notify user 404: NOT FOUND
            response.writeHead(404, {'Content-Type': 'text/html'});
            response.statusCode = 404;
        } else {
//            console.log("Servicing HTML document with no errors!"); //Used for Debug
            response.writeHead(200, {'Content-Type': 'text/html'}); 
            response.statusCode = 200;
            response.write(data.toString());
	}
        response.end();   
    });         
}

function doCGI(pathname, response){
    //Set specific environment set
    var myEnv = { 'PATH':'./MYCGI/'}
    
    pathname = pathname.slice(1);
   
    //Attempt to execute CGI file, verify that CGI file exists
    //if not, send an error message to browser and server
    //if so, service the CGI file to browser
    exec(pathname, {env: myEnv}, function(err, stdout, stderr){
        if(err){
            console.log("Exec error: "+err);
            response.writeHead(404, {'Content-Type': 'text/html'});
            response.statusCode = 404;
        } else {
//            console.log("Servicing CGI document with no errors!"); //Used for debug
            console.log("stdout: "+stdout);
            console.log("stderr: "+stderr);
            response.writeHead(200, {'Content-Type': 'text/html'});
            response.statusCode = 200;
            response.write(stdout);
        }
        response.end();
     });
}

function doPHP(pathname, response){
    var cmdvalue = paul.whatphp();
    var cmdpath = paul.ppath();

    pathname = "./MYHTML"+pathname
    var fullfilename = cmdvalue + " " + pathname;
    var myEnv = { 'PATH':cmdpath }
    
//    console.log("This is the command being sent to exec function: "+fullfilename); //Used for debug
//    console.log("This is the path of command: "+cmdpath); //Used for debug

    exec(fullfilename, {env: myEnv}, function(err, stdout, stderr){
        if(err){
            console.log(err);
            response.writeHead(404, {'Content-Type':'text/html'});
            response.statusCode = 404;
        } else {
//            console.log("Servicing PHP document with no errors!"); //Used for debug
            console.log("stdout: "+stdout);
            console.log("stderr: "+stderr);
            response.writeHead(200, {'Content-Type':'text/html'});
            response.statusCode = 200;
            response.write(stdout.toString());
        }
        response.end();
    });
}

function mylisten(server, portnum, hostname, logger){
    console.log("Inside mylisten function.");
    
    logger(portnum, hostname);
    server.listen(portnum, hostname);
}

function findaport(){
    minportnum = paul.pstart();
    maxportnum = paul.pend();
    var portnum = Math.floor(Math.random() * (maxportnum - minportnum + 1)) + minportnum;
    return portnum;
}

var server = http.createServer(myprocess);
var portnum = findaport();
var hostname = paul.phost();
var logger = paul.logger;
mylisten(server, portnum, hostname, logger);





