#Author: Kyle Shake

from flask_wtf import Form
from wtforms import StringField, BooleanField, SelectField
from wtforms.validators import DataRequired
import simplejson as json

class AForm(Form):
    jsonFile = open('myp5_4/static/Races.json')
    raceData = jsonFile.read()
    raceJSON = json.loads(raceData)
    theRaces = raceJSON['races']
    theMatches = raceJSON['match']
    theStats = raceJSON['stats']

    raceToSearch = SelectField('raceToSearch') 
    whatToMatch = SelectField('whatToMatch')
    matchValue = StringField('matchValue')
    statToFind = SelectField('statToFind') 
    maxOrmin = SelectField('maxOrmin',choices=[("Max", "Max"),("Min","Min")])
