# BigData-Project-Volleyball

The aim of this project is to produce a dataset for Bid Data course project and then train a Machine Learning algorithm. 

# Volleyball Dataset

Given a huge number of scout DataProject files, a PHP script has been developed in order to produce a dataset of matches which will feed a machine learning algorithm.
From the scout files, containing a lot of information of the match related to teams and to every single action of the match, the PHP script catches information like match date, teams identifier, percentages of Attack, Block and Service fundamentals for both teams and the final result (expressed by a identifier in order to distinguish 6 possible results: 3-0,3-1,3-2,0-3,1-3 and 2-3). 


# Machine Learning approach

Given 6 fields of the Volleyball dataset, related to Attack, Block and Service percentage for two teams in a game, the Machine Learning algorithm has to predict the type of
final ressult (e.g. Match finished as 3-0 or 3-1 or 3-2 for the first team). 
The algorithm has to be trained using several match data. The possible strategy to implement for this scope is represented by a Multinomial Logistic Regression: 
the target output value (result) can assume more than two value (6 result at least, considering the possibility of produce a dataset with more detailed scores, e.g. distinguish a 3-0 match with low gap between teams points from a 3-0 match with a huge predominance of a team on the other). 
