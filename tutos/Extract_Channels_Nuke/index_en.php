<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Introduction</h1>
<P>In this tutorial we're going to overview Nuke's scripting using the Script Editor, which allows 
to run more or less complex scripts promptly, unlike the method we saw in the tutorial about 
<?php what("making a Lookat Node in Nuke", "/2D_LookAt_in_Nuke");?>).</P>
<P>Here is our today's mission and the checkpoints ;

<OL>
	<LI><P>detect the different channels containeds in an image</P>
	<LI><P>extract these different channels with nodes</P>
	<LI><P>define a layout in our Node View to make all this visually nice</P>
</OL></P>
<P>Here you can find a little EXR image you can use for the practice of the tutorial, it contains 
some channels which can be useful for what we want to do :)</P>
<center><a href='<?php $current_dir();?>files/bruce_0000.exr'>DOWNLOAD</a></center><BR><BR>

<P>Let's start by retrieving the node selected by the user, thank to the function <B>selectedNode
</B>() in the library <B>nuke&nbsp;</B>;</P>
<?php
createCodeX("node = nuke.selectedNode()");
?>
<BR><BR>

<dt id="20"></dt><h1>Retrieving the channels</h1>
<dt id="21"></dt><h2>First technique 'of the hare'</h2>
<P>The first technique our great Master gave us was the so-called technique of the Hare, which means 
going straight to the aim, with a lightweight code, sometime you can 'hurt' the retrieved datas by 
being so fast =).
<BR>To do so we are going to loop through the attribute <B>channels</B>() of our node in order to 
get the different channels. We'll store all these channels in a 
<?php py("set", "https://docs.python.org/2/library/stdtypes.html#set");?>
, so that we keep something light, because sets are lighter than array, but keep in mind that <B>set
</B> <?php py("doesn't have index", "https://docs.python.org/2/library/sets.html");?>,
 they doesn't maintain the order of creation. </p><p>The advantage is lightness and
the fact that every element of the set have to be unique (because there is no index to make 
difference between two identical element), so this will automatically remove duplicates, which is 
EXACTLY what we want.</p>
<P>Let see what the <B>node.channels</B>() give us&nbsp;;
</P>
<?php
createCodeX("node = nuke.selectedNode()
print node.channels()
# Result : ['rgba.red', 'rgba.green', 'rgba.blue', 'rgba.alpha', 'lighting.blue', 'lighting.green', 'lighting.red', 'reflectionFilter.blue', 'reflectionFilter.green', 'reflectionFilter.red', 'specular.blue', 'specular.green', 'specular.red']
");
?>
<BR>
<P>So we get a list of all the channels with separate elements R, G and B, we are going to keep 
each channel globally, so we're going to split the elements to get only what we have before the 
<I>dot</I>, something like ;
</P>
<?php
createCodeX("node, chans = nuke.selectedNode(), set()
for chan in node.channels():
	chans.add(chan.split('.')[0]) # add is equivalent to append for a set
print chans
# Result : set(['specular', 'lighting', 'reflectionFilter', 'rgba'])");
?>
<BR>

<P>Now we get a light and clean set, containing only unique channels with two lines of codes, but 
we've lost the order ! If order matters to you, please refer to the next part =).</P>
<BR>

<dt id="22"></dt><h2>Second technique 'of the turtle'</h2>
<P>The second technique of our Great Master, is the technique fo the Turtle, depending on your 
needs or tastes, this can be useful to get the channels' list in the same order contained by the 
file, to do so we need to use a <?php py("list", "https://docs.python.org/2/tutorial/datastructures.html");?> instead of our previous set, we should get something like ;
</P>

<?php
createCodeX("node, chans = nuke.selectedNode(), list()
for chan in node.channels():
	chan_split = chan.split('.')[0]
	if chan_split not in chans:
		chans.append(chan_split)
print chans
# Result : ['rgba', 'depth', 'GI', 'SSS', 'diffuse']");
?>
<BR>

<P>So now we get the same thing, but sorted in order, no need to extend on the subject, this is 
magic of programming, there is and infinite number of ways for the same result ! Just need to find 
the one which fits more =).</P>
<BR><BR>

<dt id="30"></dt><h1>Creating nodes</h1>
<P>Now we are going to work on the big part of the script, to know create an assembly of nodes 
<I>Shuffle</I> / <I>Remove</I> / <I>Dot</I> to extract each channel and return it in a clean output 
! The <I>Shuffle</I> node will allows us to get the channel we want, and send it to RGB for 
convenience, then we're going to use the <I>Remove</I> node in '<I><B>keep</B></I>' mode to remove 
all the other channels, access to our datas will be faster for the rest of the tree this way.
</P>
<P>The last thing, more for visual comfort, is adding a <I>Dot</I> node, activating the 
'<I><B>hide_input</B></I>' attribute, which allows us to duplicate it and place it everywhere we 
want in our Nuke's tree without having big connection lines crossing the tree.
</P>
<P>Like that&nbsp;;</P>
<?php addImage("00.png", "Preview of our goal", 201);?>

<P>With a little color effect on our <I>Dot</I> node in order to notice it easily =).<br>
The creation of nodes with <B>nuke</B> library can be achieved with the sub-library <B>nodes</B>.
No parameter is necessary to nodes creation in Nuke, however we're going to use the parameters 
<B><I>name</I></B> and <B><I>inputs</I></B> to specify the name of our connections and the node 
connected in input.
</P>
<P>If we transcript that we should have something like ;</P>
<?php
createCodeX("node = nuke.selectedNodes()
shuffle_node = nuke.nodes.Shuffle(name='SHUFFLE', inputs=[node])");
?>
<BR><P>Executing these two lines a with a selected node you have a new <I>Shuffle</I> node create 
below your node.</P>
<P>
<?php
addTip("Here is a tip to know the names of all editable attributes in your Nuke's node (these 
attributes are called <B>knobs</B>), you just need to maintain the mouse above a field of your 
node, a little tooltip will appear, the bold text on the first line indicates the name you should 
use to access to your attribute ;");
addImage("01.gif", "How to know the name of node's knobs");
?>

<P>Which is very useful for us ! Now we just have to pick what we want, choose the <I>knobs</I> we 
want to edit and integrate that in our code ! The editing of a node's attribute can be achieved by 
accessing to the know this way ;
</P>
<?php
createCodeX("node = nuke.selectedNodes()
# Creating a Shuffle node
shuffle_node = nuke.nodes.Shuffle(name='SHUFFLE', inputs=[node])
shuffle_node['in'].setValue('rgba') # defining the attribute 'in' to the value 'rgba'");
?>
<BR><P>
You need to know that for simple attributes like the one we're working with, we'll only needs 
values such as strings, boolean and floats.
<BR>

Now lets apply this method to our assembly of nodes, we should get something like ;</P>
<?php
createCodeX("node = nuke.selectedNodes()
# Creating a Shuffle node
shuffle_node = nuke.nodes.Shuffle(name='SHUFFLE', inputs=[node])
shuffle_node['in'].setValue('rgba') # defining the attribute 'in' to the value 'rgba'
# we set 'postage_stamp' to True to get a preview
shuffle_node['postage_stamp'].setValue(True)
# Creating a Remove node
remove_node = nuke.nodes.Remove(name='REMOVE', inputs=[shuffle_node])
# we edit our remove node to keep one channel
remove_node['operation'].setValue('keep')
# and we keep the 'rgba' channel
remove_node['channels'].setValue('rgba')
# Creating a Dot node
dot_node = nuke.nodes.Dot(name='DOT', inputs=[remove_node])
dot_node['label'].setValue('rgba') # we define a visual label for our dot node");
?>
<BR><P>
<?php
addNote("The '<B>postage_stamp</B>' attribute indicates to Nuke that this node will display a 
preview of what he have in his RGB channel, like the Read node, this allows us to see faster what's 
inside our nodes, I guess we'll need that =) !");
?>
</P><P>Let's run our bunch of code with a selected node, we should get what we had on the preview 
above, without visual attributes like colors and '<B>hide_input</B>' activated, don't worry about 
that we're going to have a look on that right now !</P>
<BR>

<dt id="40"></dt><h1>Visual settings</h1>
<P>We're now going to set some visual details, indeed some issues come with this part. For example, 
if we enable the '<B>hide_input</B>' attribute, Nuke seems to have technical problem to maintain 
a nice layout, we'll need to help him a little bit on that =), seems fair when we see the tremendous 
amount of help he gives to us ! So we need to place this newly created node by ourselves.
<BR>
First we need to get positiong of our selected node, to do so, let's use the functions 
<B>xpos</B>() and <B>ypos</B>() of the node, so ;</P>
<?php
createCodeX("node = nuke.selectedNode()
print node.xpos(), node.ypos()
# Result : 172");
?>
<BR>
<P>Thus we get our node's position coordinates, we just need to think about a little cross product 
to get the starting point of the channels' nodes we have, with the below formula the nodes are 
going to be centered below the selected node, feel free to change it =) !</P>
<?php
createCodeX("node = nuke.selectedNode()
x, y = node.xpos() - (len(node.channels) - 1) * 50, node.ypos() + 100");
?><BR><P>
	Now let's define a correct position for our node with the <I>knobs</I> <B>xpos</B> and 
	<B>ypos</B>, like ;<BR><B>node['xpos'].setValue(172)</B>
</P>
<P>Now let's have a look to a final assembly of our parts of code, just need to integrate in a loop 
for the different channels =) !
</P>

<BR>

<dt id="50"></dt><h1>Final assembly</h1>
<P>Now we've a clue to accomplish the today's mission, we've going to put together all what we've 
done in one script, with the following scheme ;</P>
<UL>
	<LI>we get the current selection
	<LI>we get the channels of our node
	<LI>we get the position of our node
	<LI>we loop through all the channels ;
	<UL>
		<LI>we create a <I>Shuffle</I> node and we move it to the correct place
		<LI>we create a <I>Remove</I> node
		<LI>we create a <I>Dot</I> node and we move it to the correct place, below the <I>Remove</I>
		 node
		<LI>we apply a color to our <I>Dot</I>'s label with the so-called technique of the wretch
	</UL>
</UL>
<BR>

<P>Fine ! Let's see a first draft of our final assembly ;</P>
<?php
createCodeX("# Getting current selection
node, chans = nuke.selectedNode(), set()
# Getting channels inside the node
for chan in node.channels():
	chans.add(chan.split('.')[0]) # add is equivalent to append for a set
# Setting correct start place
x, y = node.xpos() - (len(chans) - 1) * 50, node.ypos() + 100
# We loop through channels
for i, chan in enumerate(chans):
	# Creating Shuffle node
	shuffle_node = nuke.nodes.Shuffle(name='SHF_%s' % chan, inputs=[node])
	shuffle_node['in'].setValue(chan) # defining 'in' attribute to the channel's value
	shuffle_node['postage_stamp'].setValue(True)
	shuffle_node['hide_input'].setValue(True)
	# We move our node to the correct position
	shuffle_node['xpos'].setValue(x)
	shuffle_node['ypos'].setValue(y)
	x += 100 # we step our x value so the next Shuffle node will be beside this one
	# Creating Remove node
	remove_node = nuke.nodes.Remove(name='REM_%s' % chan, inputs=[shuffle_node])
	remove_node['operation'].setValue('keep') # defining Remove to 'keep'
	remove_node['channels'].setValue('rgb') # we keep only 'rgb'
	# Creating Dot node
	dot_node = nuke.nodes.Dot(name='DOT_%s' % chan, inputs=[remove_node])
	dot_node['label'].setValue(chan) # defining label
	dot_node['hide_input'].setValue(True)
	# moving our Dot node to the correct position
	dot_node['xpos'].setValue(shuffle_node.xpos() + 35)
	dot_node['ypos'].setValue(shuffle_node.ypos() + 100)
	# the famous wretch technique
	dot_node['note_font_color'].setValue(4286779903)");
?>
<BR>



<P>So here is a first working draft of our script, if you drop that in a <B>Script Editor</B> tab 
and press '<B>Ctrl+Enter</B>', this will generate all the nodes extracted from the channels of the 
image ! If ever you don't have source to test it, you can use the image file available above ! You 
should see this result ;</P>
<BR>


<?php addImage("02.png", "What we get with the sample image");?>

<P>Let's just have a look to the technique of the wretch, some of you guys already understood that, 
this means defining the colour by manually, then print out the value and copy / paste it in our 
script ! Simple method for unique use like that !
</P>
<P>Our little script seems nice now ! I would just suggest to add a better naming, let's see that 
on the next step !</P>
<BR>

<dt id="60"></dt><h1>The icing on our cake</h1>
<P>By getting the name of the <I>Read</I> node where the <I>Dots</I> are connected, we can achieve 
that with a very useful library in Nuke, namely <B>nuke.tcl</B>
</P><P>We can use this library to evaluate TCL expressions, which is the internal Nuke's language 
<?php what("Here is the doc =)", "http://www.nukepedia.com/reference/Tcl/group__tcl__builtin.html");?> 
where you can pick what you need, these expressions can be used in any node's field in Nuke, and 
evaluated on every refresh.<br>
The expression we're going to use is the <B>topnode</B>, allowing us to go to the top of our 
hierarchy tree and to get the first norde, which <I>should</I> be a <I>Read</I> node, if this is 
not the case, I highly recommend you to add a naming prefix by yourself !<P>
By selecting a node in our tree, executing this little line of code will return the <I>knob</I> 
'<B>file</B>' value of the Read on top of the hierarchy.</P>
<?php
createCodeX("print nuke.tcl('knob [topnode %s].file' % nuke.selectedNode().name())
# Result : .../files/bruce_0000.exr");
?>
<BR>
<P>Nuke will give us the value of <I>knob</I> <B>file</B> on the <B>topnode</B> of our hierarchy, 
even if we go on a tree with some changes between the read and our selected node, the use of the 
<B>topnode</B> method will return us the correct value of the <I>knob</I> <B>file</B> ! Like that ;</P>

<?php addImage("03.jpg", "Even selecting a child, the script finds the Read on <b>topnode</b>");?>

<P>By selecting the node <I>Transform</I> and running our line of code, the result will be the 
same, even we don't select the <I>Read</I> node !
</P>
<P>
If we implement that in our previously written assembly, so that every new node created have a part 
of the file's name in prefix, we will prevent duplicates in our names, this should looks something 
like that ;</P>
<?php
createCodeX("# Getting current selection
node, chans = nuke.selectedNode(), set()
# We get the correct file's name
filename = nuke.tcl('knob [topnode %s].file' % node.name())
# We split it to have only tail, and again to get a good part of the file's name
nicename = '_'.join(filename.split('/')[-1].split('_')[:-1])
# Getting channels inside the node
for chan in node.channels():
	chans.add(chan.split('.')[0]) # add is equivalent to append for a set
# Setting correct start place
x, y = node.xpos() - (len(chans) - 1) * 50, node.ypos() + 100
# We loop through channels
for i, chan in enumerate(chans):
	# First we define a generic prefix used by all nodes
	base_name = '%s_%s' % (nicename, chan)
	# Creating Shuffle node
	shuffle_node = nuke.nodes.Shuffle(name='SHF_%s' % base_name, inputs=[node])
	shuffle_node['in'].setValue(chan) # defining 'in' attribute to the channel's value
	shuffle_node['postage_stamp'].setValue(True)
	shuffle_node['hide_input'].setValue(True)
	# We move our node to the correct position
	shuffle_node['xpos'].setValue(x)
	shuffle_node['ypos'].setValue(y)
	x += 100 # we step our x value so the next Shuffle node will be beside this one
	# Creating Remove node
	remove_node = nuke.nodes.Remove(name='REM_%s' % base_name, inputs=[shuffle_node])
	remove_node['operation'].setValue('keep') # defining Remove to 'keep'
	remove_node['channels'].setValue('rgb') # we keep only 'rgb'
	# Creating Dot node
	dot_node = nuke.nodes.Dot(name='DOT_%s' % base_name, inputs=[remove_node])
	dot_node['label'].setValue(chan) # defining label
	dot_node['hide_input'].setValue(True)
	# moving our Dot node to the correct position
	dot_node['xpos'].setValue(shuffle_node.xpos() + 35)
	dot_node['ypos'].setValue(shuffle_node.ypos() + 100)
	# the famous wretch technique
	dot_node['note_font_color'].setValue(4286779903)","Our final extraction script");
?>
<BR>

<P>And the result is ;</P>
<BR>

<?php addImage("04.jpg", "Final result");?>

<P>We now get the correct name to rename our nodes, the whole thing will be more usable in 
production. You can also add the correct naming convention in the <I>knob</I> '<B>label</B>' of our 
<I>Dot</I> node !</P>

<P>We may consider more steps like if the user's selection is bad, or isn't a Read, which can be 
done pretty easily with some <B>try / except</B>, maybe we'll that another time =) !</P>
