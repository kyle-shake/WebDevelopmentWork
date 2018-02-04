#Author: Kyle Shake

from flask import render_template, flash, redirect
import simplejson as json

from myp5_4 import app
from .myForms import AForm
from .mySearch import mySearch

@app.route('/')
def index():
    return render_template('index.html',title='UK STAT SEARCH')

@app.route('/search', methods=['GET', 'POST'])
def search():
    form = AForm()

    jsonFile = open('myp5_4/static/Races.json')
    raceData = jsonFile.read()
    raceJSON = json.loads(raceData)
    theRaces = raceJSON['races']
    theMatches = raceJSON['match']
    theStats = raceJSON['stats']
    form.raceToSearch.choices = [(v, k) for k, v in theRaces.items()]
    form.whatToMatch.choices = [(v, v) for k, v in theMatches.items()]
    form.statToFind.choices = [(v, v) for k, v in theStats.items()]

    if form.validate_on_submit():
        sortedRacers = mySearch(form.raceToSearch.data, form.whatToMatch.data,
                form.matchValue.data, form.statToFind.data, form.maxOrmin.data);
#DEBUG        print sortedRacers
        searchParam = [form.raceToSearch.data, form.whatToMatch.data, form.matchValue.data,
                 form.maxOrmin.data, form.statToFind.data]
        output = "Results:"
        if(len(sortedRacers)!=0):
#DEBUG            for i in sortedRacers:
#                print output
#                output = ""
#                for key in i:
#                    if (
#DEBUG                        key == 'Pace' or key == 'FinishTime' or
#                        key == 'Place' or key == 'AgePlace'
#                       ):
#                        i[key] = str(i[key])
#DEBUG                    output += key+": "+i[key]+"  |  " 
            return render_template('results.html', title="Search Results", param=searchParam,
                                  result=sortedRacers)
        elif(len(sortedRacers) == 0):
            print "No racers found!"
            flash("No racers found!")
        return redirect('/')
    return render_template('search.html',title='Search', form=form)


#@mod.route('/search', methods=['GET', 'POST'])
#def validateRaces():
#    jsonFile = open('myp5_4/static/Races.json')
#    raceData = jsonFile.read()
#    raceJSON = json.loads(raceData)
#    theRaces = raceJSON['races']
#    theMatches = raceJSON['match']
#    theStats = raceJSON['stats']
  
#    races = [(key, theRaces[) for c in 

#@app.route('/Gobbler')
#def Gobbler():

#    jsonFile = open('myp5_4/static/2016_Gobbler.json')
#    raceData = jsonFile.read()
#    runnerList = json.loads(raceData)
#    theRunners = runnerList['runners']

#    return render_template('Gobbler.html', title="2016 Gobbler Run Results", result=theRunners)

#@app.route('/Ironhorse')
#def Ironhorse():
#
#    jsonFile = open('myp5_4/static/2016_Gobbler.json')
#    raceData = jsonFile.read()
#    runnerList = json.loads(raceData)
#    theRunners = runnerList['runners']
#
#    return render_template('Ironhorse.html'), title="2016 Ironhorse Race Results", result=theRunners)

@app.route('/<name>')
def displayRace(name):
    
    #Verify correct filename
    racesFile = open('myp5_4/static/Races.json')
    racesData = racesFile.read()
    racesJSON = json.loads(racesData)
    theRaces = racesJSON['races']

    whichfile = ""
    validfile = False
    for race, filename in theRaces.items():
        if name in filename:
            validfile = True
            whichfile = filename

    if not validfile:
        return []

    #Load verified file

    filename = "myp5_4/static/" + whichfile
    jsonFile = open(filename)
    raceData = jsonFile.read()
    runnerList = json.loads(raceData)
    theRunners = runnerList['runners']

    return render_template('stats.html', title="Search Results", name=name,
                           result=theRunners)
 
