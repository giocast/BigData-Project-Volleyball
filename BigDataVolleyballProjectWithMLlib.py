from pyspark.mllib.classification import LogisticRegressionWithLBFGS
from pyspark import SparkContext, SparkConf
from pyspark.mllib.regression import LabeledPoint
from pyspark.sql import SparkSession

import numpy as np



conf = SparkConf().setAppName("ProjectVolleyballMLlib")
sc = SparkContext(conf=conf) 

logData = SparkSession.builder.appName("ProjectVolleyball").getOrCreate()
dataframe = logData.read.option("header",True).csv("/home/giocast/spark-3.0.1-bin-hadoop3.2/BigDataVolleyballScouts/BigDataVolleyballProject.csv")
dataframe.show();  
X =  np.array(dataframe.select("PercAttaccoA", "PercAttaccoB", "PercMuroA", "PercMuroB", "PercBattutaA", "PercBattutaB").collect(), dtype='i');

y = np.ravel(np.array(dataframe.select("EsitoIncontroPerML").collect(), dtype='i'));




numeroRighe = dataframe.count();
parsed_data = [];

i = 0
while i < numeroRighe:
    parsed_data.append(LabeledPoint(y[i], X[i]))
    #print("parsed_data %s " % parsed_data[i]); print("y[i] %s" %y[i]); print("X[i] %s" %X[i]);
    i = i + 1;


model = LogisticRegressionWithLBFGS.train(sc.parallelize(parsed_data),  iterations=1000, numClasses=6);



esito = model.predict([31,35,-2,-9,-1,3]) 

print("PREDIZIONEEEEEEEEEEEEEEEEEE %s" % esito); 


logData.stop();
