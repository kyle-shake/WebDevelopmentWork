#Author: Kyle Shake

from flask import render_template, flash, redirect
from myp5_4 import app
from .myForms import AForm
import simplejson as json

def timeToSeconds(Time):
#    print type(Time)   DEBUG

    #Cut off any fractional seconds
    if "." in Time:
        Time = Time.split(".")[0]
    timelist = Time.split(":")
    totalsecs = 0
    if (len(timelist) == 3):
        totalsecs = int(timelist[0])*3600 + int(timelist[1])*60 + int(timelist[2])
    elif (len(timelist) == 2):
        totalsecs = int(timelist[0])*60 + int(timelist[1])
    else:
        print "Error in time conversion... \n timelist contains"
        print timelist
    return totalsecs

def mySearch(a1, b1, c1, d1, e1):
    
    #Verify correct filename
    racesFile = open('myp5_4/static/Races.json')
    racesData = racesFile.read()
    racesJSON = json.loads(racesData)
    theRaces = racesJSON['races']
    
    validfile = False
    for race, filename in theRaces.items():
        if filename == a1:
            validfile = True

    if not validfile:
        return []

    #Load verified file
    file2search = "myp5_4/static/" + a1
    jsonFile = open(file2search)
    raceData = jsonFile.read()
    raceJSON = json.loads(raceData)
    theRacers = raceJSON['runners']
    
    #Handle 'Nothing' Search Parameter
    if(b1 != 'Nothing'):
        racersFound = filter(lambda person: c1 in person[b1], theRacers)
#DEBUG        print racersFound
    elif(b1 == 'Nothing'):
        racersFound = theRacers

    #If Racers found, convert Pace & Finish Time to seconds
    # convert Place, Age and AgePlace to int
    #then sort list of racers using built in sorted function
    if(len(racersFound)!=0):    
        for i in racersFound:
            i['Pace'] = timeToSeconds(i['Pace'])
            i['FinishTime'] = timeToSeconds(i['FinishTime'])
            i['Place'] = int(i['Place'])
            if(len(i['Age']) > 0):
                i['Age'] = int(i['Age'])
            i['AgePlace'] = int(i['AgePlace'])
    if(e1 == "Min"):
        sortedRacers = sorted(racersFound, key=lambda k: k[d1])
    elif(e1 == "Max"):
        sortedRacers = sorted(racersFound, key=lambda k: k[d1], reverse=True)
#DEBUG    print sortedRacers
    return sortedRacers
