#Group: Jintong Hou, Ifeanyi Osuchukwu, Samuel Yoon
#Jintong is responsible for NpInputforRisk.py
try:
    import sys,os; 
    import sqlite3
    import pandas as pd
    import numpy as np #required library
    import matplotlib.pyplot as plt#required library
    import statsmodels.formula.api as smf
    from sklearn import metrics
    #import shutil
    import statistics
    import tempfile
except: print("importissue")
 
#import requests
#print(sys.argv)
try:
    tempdir=sys.argv[1]
    tempdir=tempdir.replace(os.sep,'/')
except: print("dbissue")

try:
    year1in=sys.argv[2];
    year2in=sys.argv[3];
except: print("inputissue")

sqlcond2="select Diabetes from DMdata where (Diabetes =0 or Diabetes = 1) "#for no input predictors

if len(year1in)>0:year1=float(year1in);
if len(year2in)>0:year2=float(year2in);

if len(year1in)>0 and len(year2in)==0: 
    sqlcond2=sqlcond2+"and SurveyYear >="+year1in;
if len(year1in)==0 and len(year2in)>0: 
    sqlcond2=sqlcond2+"and SurveyYear <="+year2in;
if len(year1in)>0 and len(year2in)>0: 
    sqlcond2=sqlcond2+"and SurveyYear <= "+year2in + " and SurveyYear >= "+year1in ;
    
#print(sqlcond2)   
#get sql data
#if os.path.exists(tempdir+'/DMdata.db')==False: shutil.copyfile('datatemplate/DMdata.db',tempdir+'/DMdata.db')
try:
    dmdata=tempdir+'/DMdata.db'
    conn = sqlite3.connect(dmdata);
    conn.commit();
    cur = conn.cursor();
    cur.execute(sqlcond2)
    rs=cur.fetchall();
    df=pd.DataFrame(rs)
except: print("dbissue")
try:
    dflists=df.values.tolist();
    dflist=[]
    for e in dflists:dflist.append(e[0]);
#print(sqlcond2,df)
    bad=-1
    if len(dflist)>0: print(statistics.mean(dflist));
    else: print(bad)
    print(len(dflist))
except: print("calissue")
