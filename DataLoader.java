



import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import org.jgrapht.*;
import org.jgrapht.graph.*;

public class DataLoader {
    static final String DB_NAME = "edges.sqlite";
    static final int LIMIT = 100;
    
    public DataLoader() throws Exception {
    }
    
    public Graph getGraph(int limit) throws Exception {
        Class.forName("org.sqlite.JDBC");
        Graph<Integer, DefaultEdge> graph = new SimpleGraph<Integer, DefaultEdge>(DefaultEdge.class);
        Connection connection = null;
        connection = DriverManager.getConnection("jdbc:sqlite:" + DB_NAME);
        Statement statement = connection.createStatement();
        statement.setQueryTimeout(30);  // set timeout to 30 sec.
        ResultSet rs = statement.executeQuery("select node_a, node_b from edges limit " + limit);
        while(rs.next())
        {
            int node_a = rs.getInt("node_a");
            int node_b = rs.getInt("node_b");
            graph.addVertex(node_a);
            graph.addVertex(node_b);
            graph.addEdge(node_a, node_b);
        }
        return graph;
    }
}
