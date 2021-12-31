#Group: Jintong Hou, Ifeanyi Osuchukwu, Samuel Yoon
#Jintong is responsible for Summary_Statistic.py
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

try:
    tempdir=sys.argv[1]
    tempdir=tempdir.replace(os.sep,'/')
except: print("dbissue")

try:
    var=sys.argv[2]
    year1in=sys.argv[3];year2in=sys.argv[4];
except: print("inputissue")


#build sql statement condition
sqlwhere="";
if var != "SurveyYear":
    if var=="npreg": 
        xlab="Number of pregnancies"; 
        sqlwhere=" >=0 ";
    if var=="glucose": xlab="Glucose";sqlwhere=" >0 ";
    if var=="bmi": xlab="BMI";sqlwhere=" >0 ";
    if var=="ped": xlab="Diabetes pedigree function value";sqlwhere=" >0 ";
    if var=="age": xlab="Age";sqlwhere=" >0 ";
    sqlcond="select "+ var + " from DMdata where " + var + sqlwhere
elif var =="SurveyYear":
    sqlcond="select "+ var + " from DMdata where ID is not NULL " 
if len(year1in)>0:year1=float(year1in);
if len(year2in)>0:year2=float(year2in);

if len(year1in)>0 and len(year2in)==0: sqlcond=sqlcond+"and SurveyYear >="+year1in;
if len(year1in)==0 and len(year2in)>0: sqlcond=sqlcond+"and SurveyYear <="+year2in;
if len(year1in)>0 and len(year2in)>0: sqlcond=sqlcond+"and SurveyYear <= "+year2in + " and SurveyYear >= "+year1in ;
#print(sqlcond)
#if os.path.exists(tempdir+'/DMdata.db')==False: shutil.copyfile('datatemplate/DMdata.db',tempdir+'/DMdata.db')
try:
#if 1==1:
    dmdata=tempdir+'/DMdata.db'
    conn = sqlite3.connect(dmdata);
    conn.commit();
    cur = conn.cursor();
    cur.execute(sqlcond)
    rs=cur.fetchall();
    df=pd.DataFrame(rs)
except: print("dbissue")

#try:
if 1==1:
    dflists=df.values.tolist()
    dflist=[]
    for el in dflists:
        try: 
            el=float(el[0])#remove non-numbers
            dflist.append(float(el));
        except: pass
    strdflist=dflists.copy()


    if var != "SurveyYear":
        print(statistics.mean(dflist));
        print(statistics.stdev(dflist));
        print(statistics.median(dflist));
        print(min(dflist));print(max(dflist));
    elif var== "SurveyYear":
        num=0
    
        for e in dflist:
            if pd.isna(e):# to calculate percentage of missing values
                e=9999
                dflist[num]=e
                strdflist[num]=str(e)
            else:
                strdflist[num]=str(int(e))
            num=num+1
            
        d={x:dflist.count(x) for x in dflist};
        strd={x:strdflist.count(x) for x in strdflist};
        klist=list(strd.keys());vlist=list(strd.values());
        print(klist[:]);print(vlist);
    
    print(len(dflist))
 
 
    if var != "SurveyYear":
        plt.hist(dflist)
        plt.xlabel(xlab)
        plt.ylabel("Frequency")

        histFileDir=tempfile.TemporaryFile(suffix='.png',dir=tempdir,mode='w',delete=False);

        plt.savefig(histFileDir.name);
        histFileDir.close()
        plt.close();
        print(histFileDir.name)

#except: print(dflist,"calissue") 
