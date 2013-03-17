i figured we could use sqlite for this. its an easy to install, lightweight sql database. 

you can use a firefox addon to query/view the data quickly.
https://addons.mozilla.org/en-us/firefox/addon/sqlite-manager/

seems like sqlite part of py's std library too
http://docs.python.org/2/library/sqlite3.html

technically all we need is the `node_events` table, but I included the `nodes` and `events` tables too. 
the only purpose for the other tables is to provide textual names for the nodes(eg character name) and events(eg "grats level 10").
otherwise, were staring at integers, which are harder to comprehend. for convenience, i made a 
database view named denormalized_node_events which joins those tables.


this query gives us a list of all other characters who have one or more events in common to the character named "Huntarrd". 
it also lists how many events in common they have.



select ne2.node_name
     , count(*) num_common_events
  from denormalized_node_events ne1
inner
  join denormalized_node_events ne2
    on ne1.node_id != ne2.node_id
   and ne1.event_id = ne2.event_id
   and ne1.event_ts = ne2.event_ts
 where ne1.node_name = 'Huntarrd'
 group
    by ne2.node_id
 order
    by num_common_events desc
	
	
	
If you run that youll see there's a couple characters who have 3 events in common to Huntarrd, and some more who have only 1 or 2 events in common.
The characters who have 3 in common form the max clique.
So, Huntarrd, Clamcrusher, and Cheesytot are all the same account. Thats my account.

If you search for 'Tristrum' instead of 'Huntarrd', youll see another max clique which has 19 common events. That's a friends account.


Anyway, we basically run that query for every character, and well find all max cliques. It can be done faster though.