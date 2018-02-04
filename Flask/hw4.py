#!/usr/bin/python
from myp5_4 import app
import random

if __name__ == '__main__':
    pMIN = 9999
    pMAX = 19999
    portNum = random.randint(pMIN, pMAX)
    app.run(host='0.0.0.0',debug=True,use_reloader=False,port=portNum)
