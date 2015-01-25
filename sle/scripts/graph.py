#!/usr/bin/env python
# a bar plot with errorbars
import os
os.environ['MPLCONFIGDIR'] = '/tmp/'
import numpy as np
import matplotlib
matplotlib.use('Agg')
import pylab
import matplotlib.pyplot as plt
import sys
#get the input string and split it into X-axis labels, values, error bars
input =[sys.argv[1].split('Z')]
#split the X-axis labels element into an array of those labels
XLabels = input[0][0].split('Y')
#get a list of the siries colours
COL = input[0][1].split('Y')
#split the means up into siries
SeriesV = input[0][2].split('Y')
print(SeriesV)
#split the error bars up into siries
SeriesE = input[0][3].split('Y')
print(SeriesE)
#Error bar color
ErrCol = input[0][4]
#Ylabel
YLabel =input[0][5]
Means = [x for x in range(0,len(SeriesV))]
for index in range(len(Means)):
	Means[index] = SeriesV[index].split('X')
	Means[index] = [float(x) for x in Means[index]]
Std = [x for x in range(0,len(SeriesE))]
for index in range(len(Std)):
	Std[index] = SeriesE[index].split('X')
	Std[index] = [float(x) for x in Std[index]]
#number of bars for each series
N = len(XLabels)
#array of the error bars
ind = np.arange(N)  # the x locations for the groups
width = 0.3       # the width of the bars
fig, ax = plt.subplots()
rects = [0 for x in xrange(len(Means))]
for index in range(len(Means)):
	print (index)
	wid = width*(index)
	print(wid)
#	rects[index] = ax.bar(ind + wid , Means[index], width, yerr=Std[index])
	rects[index] = ax.bar(ind + wid , Means[index], width, color=("#" + COL[index]), yerr=Std[index],error_kw=dict(ecolor=("#" + ErrCol),lw=2,capsize=5,capthick=2))
# add some lables
ax.set_ylabel(YLabel)
#ax.set_title('Scores by group and gender')
ax.set_xticks(ind+width)
#array of X axis lables
ax.set_xticklabels( XLabels )
ax.set_xlabel('Question')
#legend lables
#ax.legend( (rects[0][0], rects[1][0], rects[2][0]), ('woMen', 'men', 'Unspecified') )
#save to tempery file
print('Hello');
plt.savefig('/var/www/sle/temp.png')
#plt.show()
print('hello');
