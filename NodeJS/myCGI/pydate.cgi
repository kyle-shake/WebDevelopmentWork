#!/usr/bin/python
import subprocess

print 'Content-type: text/html\n\n'

print 'The current date and time is:\t'
print subprocess.Popen("/bin/date",shell=True,stdout=subprocess.PIPE).stdout.read()




