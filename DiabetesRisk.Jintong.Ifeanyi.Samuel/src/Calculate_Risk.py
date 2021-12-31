#Group: Jintong Hou, Ifeanyi Osuchukwu, Samuel Yoon
#Jintong is responsible for Calculate_Risk.py
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
#buid formula based on user input
myformula="Diabetes ~ 1 "
#build sql statement condition
sqlcond1="select npreg , glucose , BMI , ped , age , Diabetes from DMdata where (Diabetes =0 or Diabetes = 1) "

#build dictionary for individual prediction
predin=dict()
try:
    npregin=sys.argv[2];glucosein=sys.argv[3];BMIin=sys.argv[4];pedin=sys.argv[5];agein=sys.argv[6]
    year1in=sys.argv[7];year2in=sys.argv[8];
except: print("inputissue")

if len(npregin)>0:
    npreg=float(npregin);
    myformula=myformula+"+ npreg";predin["npreg"]=npreg;
    sqlcond1=sqlcond1 + " and npreg >=0 and npreg <100 "
if len(glucosein)>0:
    glucose=float(glucosein);
    myformula=myformula+"+ glucose" ;predin["glucose"]=glucose;
    sqlcond1=sqlcond1 + " and glucose > 0 and glucose<1000 "
if len(BMIin)>0:
    BMI=float(BMIin);
    myformula=myformula+"+ BMI";predin["BMI"]=BMI;
    sqlcond1=sqlcond1 + " and BMI >0 and BMI < 150"

if len(pedin)>0:
    ped=float(pedin);
    myformula=myformula+"+ ped";predin["ped"]=ped;
    sqlcond1=sqlcond1 + " and ped >0 and ped < 1000"
if len(agein)>0:
    age=float(agein);
    myformula=myformula+"+ age";predin["age"]=age;
    sqlcond1=sqlcond1 + " and age >0 and age <150"
if len(year1in)>0:year1=float(year1in);
if len(year2in)>0:year2=float(year2in);

if len(year1in)>0 and len(year2in)==0:
    sqlcond1=sqlcond1+" and SurveyYear >="+year1in;
if len(year1in)==0 and len(year2in)>0:
    sqlcond1=sqlcond1+" and SurveyYear <="+year2in;
if len(year1in)>0 and len(year2in)>0:
    sqlcond1=sqlcond1+" and SurveyYear <= "+year2in + " and SurveyYear >= "+year1in ;


#if os.path.exists(tempdir+'/DMdata.db')==False: shutil.copyfile('datatemplate/DMdata.db',tempdir+'/DMdata.db')
try:
    dmdata=tempdir+'/DMdata.db'
    conn = sqlite3.connect(dmdata);
    conn.commit();
    cur = conn.cursor();
####################################################if at least one input predictors###########################################
    cur.execute(sqlcond1)#extract data
    rs=cur.fetchall();
    df=pd.DataFrame(rs)
except: print("dbissue")


#print(sqlcond1)
#print(df)
try: 
#if 1==1:
    if len(df)==0 or len(df)<5:# do not perform model if nobs<5
        print("nobs");print(len(df));
    else:
        df.columns = [x[0] for x in cur.description];
        model = smf.logit(myformula, data=df)
        results = model.fit(disp=0)
        mypredict=results.predict(exog=predin)
        print(str(round(mypredict[0]*100,1))+"%")#0 risk

        print("Number of Observations: ",results.nobs)#1
        print("Df Residuals: ",int(results.df_resid))
        print("DF Model: ",int(results.df_model))#3
        print("AIC: ",round(results.aic,3))
        print("BIC: ",round(results.bic,3))#5
        print("Pseudo Rsquared: ",round(results.prsquared,3))
        print("Log-Likelihood full: ",round(results.llf,3))#7
        print("Log-Likelihood null: ",round(results.llnull,3))
        print("Likelihood Ratio P value: ",round(results.llr_pvalue,3))#9


        predictprob=results.predict()
        ytrue=df['Diabetes']
        print(metrics.roc_auc_score(ytrue, predictprob))#auc value
#below plot roc curve

        fpr, tpr, thresholds = metrics.roc_curve(ytrue, predictprob)
        plt.plot(fpr, tpr)

        plt.title("ROC Curve")

        plt.xlabel("False Positive Rate")

        plt.ylabel("True Positive Rate")
        rocFileDir=tempfile.TemporaryFile(suffix='.png',dir=tempdir,mode='w',delete=False);

        plt.savefig(rocFileDir.name);
        rocFileDir.close()
        plt.close();

        print(results.params)
        print(results.conf_int())
        print(results.tvalues)
        print(results.pvalues)
        print(rocFileDir.name)
except: print("calissue")