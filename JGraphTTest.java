

import java.util.Collection;
import java.util.Set;
import org.jgrapht.Graph;
import org.jgrapht.alg.BronKerboschCliqueFinder;
import org.jgrapht.graph.DefaultEdge;

public class JGraphTTest {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) throws Exception {

		DataLoader dl = new DataLoader();
		Graph<Integer, DefaultEdge> graph = dl.getGraph(100000);
		BronKerboschCliqueFinder finder = new BronKerboschCliqueFinder(graph);
		long startTime = System.currentTimeMillis();
		Collection<Set<Integer>> maxCliques = finder.getAllMaximalCliques();
		long estimatedTime = System.currentTimeMillis() - startTime;
		System.out.println(estimatedTime/1000);
		for (Set set : maxCliques) {
			//System.out.println(set);
		}

    }
}
