from pyspark.mllib.classification import LogisticRegressionWithLBFGS
from pyspark import SparkContext, SparkConf
from pyspark.mllib.regression import LabeledPoint
from pyspark.sql import SparkSession

import numpy as np

# SQUADRA A | SQUADRA B || EsitoML(*)
#     3		|	  0		||	 0
#     3		|	  1		||	 1
#     3		|	  2		||	 2
#     0		|	  3		||	 3
#     1		|	  3		||	 4
#     2		|	  3		||	 5

# PERCENTUALI
# Attacco A, Attacco B, Muro A, Muro B, Battuta A, Battuta B

# DATASET generato usando il linguaggio PHP e contenetnte 900 righe (informaizioni di partite uniche prelevate da file Scout)

conf = SparkConf().setAppName("ProjectVolleyballMLlib")
sc = SparkContext(conf=conf) 

logData = SparkSession.builder.appName("ProjectVolleyball").getOrCreate()
dataframe = logData.read.option("header",True).csv("/home/giocast/spark-3.0.1-bin-hadoop3.2/BigDataVolleyballScouts/BigDataVolleyballProjectFINAL.csv")
dataframe.show();  
X =  np.array(dataframe.select("PercAttaccoA", "PercAttaccoB", "PercMuroA", "PercMuroB", "PercBattutaA", "PercBattutaB").collect(), dtype='i');

y = np.ravel(np.array(dataframe.select("EsitoIncontroPerML").collect(), dtype='i')); #ravel pone tutti elementi su una riga




numeroRighe = dataframe.count();
parsed_data = [];

i = 0
while i < numeroRighe:
    parsed_data.append(LabeledPoint(y[i], X[i]))
    #print("parsed_data %s " % parsed_data[i]); print("y[i] %s" %y[i]); print("X[i] %s" %X[i]);
    i = i + 1;

#esito può assumere 6 valori
model = LogisticRegressionWithLBFGS.train(sc.parallelize(parsed_data),  iterations=1000, numClasses=6); 


esito = model.predict([23,24,-5,-3,28,30])

#SIMULAZIONI
#-------------------------------------------------------------------------------------------------------------
# fornisco 24,22,-5,-20,20,5 e come sperato ottengo una vittoria per squadra A (esito ML 1 [3-1])
# fornisco 22,24,-20,-5,5,20 e come sperato ottengo una vittoria per squadra B (esito ML 4 [1-3])
# fornisco 10,24,-20,-5,5,20 e come sperato ottengo una vittoria secca per squadra B (esito ML 3 [0-3])
# fornisco 24,23,-3,-5,30,28 e come sperato ottengo una vittoria al tie-break per squadra A (esito ML 2 [3-2])
# fornisco 23,24,-5,-3,28,30 e come sperato ottengo una vittoria al tie-break per squadra B (esito ML 5 [2-3])

#questi dati corrispondono a un possibile 3-2 (esito ML 3)
#esito = model.predict([31,35,-2,-9,-1,3]) 

print("PREDIZIONEEEEEEEEEEEEEEEEEE %s" % esito); 


logData.stop();
