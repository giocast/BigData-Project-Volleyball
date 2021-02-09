# BigData-Project-Volleyball

The aim of this project is to produce a dataset for Big Data course project and then train a Machine Learning algorithm to predict volleyball matches final score by getting percentage on some fundamentals. 

# Volleyball Dataset (generated using PHP)

Given a huge number of scout DataProject files, a PHP script has been developed in order to produce a dataset of matches which will feed a machine learning algorithm.
From the scout files, containing a lot of information of the match related to teams and to every single action of the match, the PHP script catches information like match date, teams identifier, percentages of Attack, Block and Service fundamentals for both teams and the final result (expressed by a identifier in order to distinguish 6 possible results: 3-0,3-1,3-2,0-3,1-3 and 2-3). 


# Machine Learning approach (designed using MLlib Apache Spark's module - sklearn module and Python)

Given six fields of the Volleyball dataset, related to Attack, Block and Service percentage for two teams in a game, the Machine Learning algorithm has to predict the type of final ressult (e.g. Match finished as 3-0 for the first team). 
The algorithm has to be trained using several match data. The possible strategy to implement for this scope is represented by a Multinomial Logistic Regression: 
the target output value (result) can assume more than two value (six result at least, considering the possibility of producing a dataset with more detailed scores, e.g. distinguish a 3-0 match with low gap between teams points from a 3-0 match with a huge predominance of a team on the other).
The Multinomial Logistic Regression helps correlating a single field value with more than two fields (six in this case).

The final aim is to provide a set of percentage of a hypotetic match (Attack, Block and Service percentages for both teams) and receive from the ML algotithm the prediction based on the previous training. 
