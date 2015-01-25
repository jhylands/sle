#!/usr/bin/env python
# a bar plot with errorbars
import numpy as np
import matplotlib.pyplot as plt

#number of bars
N = 10
#array of the scores
menMeans = (6,6,6,6,6,6,6,6,6,6)
#array of the error bars
menStd =   (0,0,0,0,0,0,0,0,0,0)

ind = np.arange(N)*1.2  # the x locations for the groups
width = 0.35       # the width of the bars

fig, ax = plt.subplots()
rects1 = ax.bar(ind, menMeans, width, color='r', yerr=menStd)

womenMeans = (1,1,1,1,1,2,2,2,2,2)
womenStd =   (0,0,0,0,0,0,0,0,0,0)
rects2 = ax.bar(ind+width, womenMeans, width, color='y', yerr=womenStd)

unspecifiedMeans = (1,2,3,4,5,6,7,8,9,10)
unspecifiedStd =   (0,0,0,0,0,0,0,0,0,0)
rects3 = ax.bar(ind+width*2, unspecifiedMeans, width, color='g', yerr=unspecifiedStd)

# add some lables
ax.set_ylabel(ind)
ax.set_title('Scores by group and gender')
ax.set_xticks(ind+width)
#array of X axis lables
ax.set_xticklabels( ('G1','G2') )
#legend lables
ax.legend( (rects1[0], rects2[0], rects3[0]), ('woMen', 'men', 'Unspecified') )
#save to tempery file
plt.savefig('temp.png')
