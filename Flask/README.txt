File: README.txt
Author: Kyle Shake
Date: 4/25/17

This README pertains to the Flask framework contained in this directory and its subdirectories

Purpose: 

	This framework provides a python based web application that searches
	a set of JSON files.

To Run:

	type "./hw4.py" into the command line

	record server port where framework is located, reported by terminal

	type <localhost>:port#
		* ex "0.0.0.0:9999" or "violet.cs.uky.edu:9999"

	this should display the framework and allow for use of the Search function

UPDATES:
4/28 -- Finished testing. Everything works great save for one error message on
        every page load. 

4/27 -- Finished the framework as a whole; testing now

4/25 -- Finished form population and most of search function
     -- List of racers found using native python function @filter
     -- NEED: Alter sorting procedure in mySearch.py 
        *Function incorrectly sorts Pace and Finish Time
     -- NEED: Alter display of results from search
        *Printing the matching runners using Flash doesn't look good
