Note that photoshop only allows for px dimensions so to scale into em use the conversion factor that 1 em is equal to 16 px.

Also for percentages, if the width of the object is 100% subtract the magins, then the width was set in css to be 
	calc(100% - [x]em) where [x] is the margin value that was 		equated into em from px to scale and size correctly (i.e. 	from the first note.)

E.g.) the shopping cart height was equated to be:
	calc(100% - 5.8em), where 5.8em is the size of the the 	navigation	bar (93px/16px -> 5.8125em). Then position would 	be: {position: fixed; top: 5.8em; right: 0em; }