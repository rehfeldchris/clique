import sqlite3
import networkx as nx
from time import time



def getGraph():
	"""assembles a graph datastructure from a database"""
	conn = sqlite3.connect('edges.sqlite')
	c = conn.cursor()
	sql = "select node_a, node_b from edges limit 4000000"
	G = nx.Graph()
	
	for row in c.execute(sql):
		node_a = int(row[0])
		node_b = int(row[1])
		G.add_edge(node_a, node_b)
		
		
		
	sql = "select node_a, node_b from edges where node_a in (295228, 112935, 140040, 292052, 364134) or node_b in (295228, 112935, 140040, 292052, 364134)"
	for row in c.execute(sql):
		node_a = int(row[0])
		node_b = int(row[1])
		G.add_edge(node_a, node_b)

	return G
		
G = getGraph()
ts = time()
cliques = list(nx.find_cliques(G))
te = time()
print "algo time {0:f} seconds \n".format(te-ts)
print "num max cliques {0:d}\n".format(len(cliques))
#print cliques