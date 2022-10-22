<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Concept</h1>
<P>In this tutorial we're going to learn how to handle our own transforms on Nuke's nodes with a little practice ; making a node which 'look at' an other Transform node.</P>

<P>Concept is very simple, we're going to get the relative positions of he source and target, calculate the angle between these two positions  using Python, thank to the Expression Tool, we'll be able to insert this little script on any kind of node !</P>

<P>If we make a fast search on <?php what("trigonometry", "http://en.wikipedia.org/wiki/Trigonometry");?>'s rules we learn that ;</P>
<font size=4 style='margin-left:60px'>tan &alpha; 
= A / B</font>
<P>so</P>
<font size=4 style='margin-left:60px'>&alpha; = atan ( A / B ) 
</font>
<P>The calculation will be very easy ! We just need to calculate the difference between the source's position and the target's position, divide source by target, apply the inverse function of tangent, we get the result in radians.</P>

<dt id="20"></dt><h1>First part - Making the core</h1>
<dt id="21"></dt><h2>Setting and using expression's nodes</h2>
<P>Let's start by creating a Transform node (shortcut <B>T</B>,
or <B>Tab</B> then type '<I>Transform</I>')</P>
<?php addImage("06.jpg", "Transform Node");?>

<P>in the <B>rotate</B> attribute field of our Transform node, press Right-Click Mouse Button over the icon on the right of the field</P>

<?php addImage("04.jpg", "Adding an expression on the rotate attribute of our Transform");?>
<P>then click on '<I>Add expression</I>...'</P>
<dt id="22"></dt><h2>Working with Expression's nodes</h2>

<P ALIGN=LEFT STYLE="widows: 1">A window will show up, you can write some code in the text field which will be interpreted by Nuke before being sent in the node's attribute value (<B>rotate
</B>in our case), by default this field is in <?php what("TCL", "http://fr.wikipedia.org/wiki/Tool_Command_Language");?> language.</P>
<?php addImage("11.jpg", "Our expression window");?>

<P>Three buttons can be found on the right of the text field,</P>
<table><tr><td><div class='Qt_Button'>&hellip;</div></td><td width=25>:</td><td> Allows you to enlarge the text field to make it multi-line</P>
</td></tr><tr><td><div class='Qt_Button'>Py</div></td><td>:</td><td> allows you to switch the builder of the expression to Python, which means our code can now be typed in Python Language !</P>
</td></tr><tr><td><div class='Qt_Button'>R</div></td><td>:</td><td> if we check this one, the builder will wait for the final value of a variable named <b>ret</b>, which is basically equivalent to the <b>return</b> function in Python</P></td></tr></table>

<P>So that's fine, we need all three of these buttons ! The first one will provide us more visibility, the second one will allows us to insert more complex code, and finally the last one will allows us to have a clean return of our code.</P>
<?php addImage("01.jpg", "The expression window, with the three buttons checked");?>
<dt id="23"></dt><h2>Few code lines =)</h2>
<P>We're now going to import the necessary Python's library for our job, known as <?php py(" math", "https://docs.python.org/2/library/math.html");?>,
which contains two useful functions ; <B>atan</B> and <B>degrees</B>.</P>
<P STYLE="font-weight: normal">
<?php createCodeX("from math import atan, degrees");
?></P>
<P STYLE="font-weight: normal">Accessing to internal Nuke's functions can be achieved with the 
<B>nuke</B> library,
the correct syntax to get the value of a node can be ;</P>
<P STYLE="font-weight: normal">
<?php createCodeX("nuke.toNode('Transform').knobs()['translate'].getValue()");?>
</P>
<P><I>Note that there is different ways to get this value, we'll later why we used this way</I></P>
<P>This line of code about will get the '<i>translate</i>' attribute of our node named '<B>Transform</B>'
and will return <B>[
0.0 , 0.0 ]</B></P>
<P>corresponding to the X and Y translate's values</P>
<P>This only line will allows us to build our script ;
the next lines will helps us to get the attributes of our two Transforms, already created, one named '<I><B>Source</B></I>'
and the other '<I><B>Aim</B></I>'.</P>
<?php addImage("03.jpg", "The two Transform nodes we're going to use");?>
<P>
<?php createCodeX("from math import atan, degrees\n\n source = nuke.toNode('Source').knobs()['translate'].getValue()\n aim = nuke.toNode('Aim').knobs()['translate'].getValue()");?>
</P>

<P>Now we can substract these two arrays</P>
<P>
<?php createCodeX("deltaX,deltaY = source[0]-aim[0],source[1]-aim[1]");?>
</P>

<P>And get the angle between the two positions thank to this formula :</P>
<P>
<?php createCodeX("angle = degrees(atan(deltaY/deltaX))");?>
</P>

<P>If we assemble the whole thing, replacing the variable <B>angle</B> by <B>ret</B>,
which is necessary to have our code correctly read by Nuke ;</P>
<P>
<?php createCodeX("from math import atan, degrees\n\n source = nuke.toNode('Source').knobs()['translate'].getValue()\n aim = nuke.toNode('Aim').knobs()['translate'].getValue()\n deltaX,deltaY = source[0]-aim[0],source[1]-aim[1]\n\n ret = degrees(atan(deltaY/deltaX))");
?>
</P>

<P>Now if we try to move on Transform node named '<I><B>Aim</B></I>',
the gizmo of the Transform node named '<I><B>Source</B></I>'
will look at the other node.</P>
<P>At this point our code is enough, indeed, we are evaluating all the possibilities, first there is the case where deltaX = 0, which will end with a <?php py("ZeroDivisionError", "https://docs.python.org/2/library/exceptions.html#exceptions.ZeroDivisionError");?>,
and then the possibility of having a sign switch of '<I>deltaX</I>'
and '<I>deltaY</I>',
if we solve these three exceptions using a <I>try</I>/<I>except</I>/<I>finally</I> we get;:</P>

<P>
<?php 
createCodeX("from math import atan, degrees\n\n source = nuke.toNode('Source').knobs()['translate'].getValue()\n aim = nuke.toNode('Aim').knobs()['translate'].getValue()\n deltaX,deltaY = source[0]-aim[0],source[1]-aim[1]\n\n try:\n
    angle = degrees(atan(deltaY/deltaX))\n except ZeroDivisionError:\n
    angle = 90 if deltaY&lt;=0 else 270\n finally:\n
    ret = angle - (180 if deltaX&lt;=0 else 0)");
?>
</P>
<P STYLE="font-weight: normal"><BR>
</P>
<dt id="24"></dt><h2>Ending of the first part</h2>
<P STYLE="font-weight: normal">Now our node is correctly looking at the other one, whatever is his position !</P>
<P STYLE="font-weight: normal">The last thing to handle, is the attribute '<I>center</I>' of our Transform node '<I><B>Source</B></I>',
to achieve that we'll have to add a value to our '<I>translate</I>' attribute, the value of the attribute '<I>center</I>',
so the user can place himself his node on the right place, or animate it.</P>
<P STYLE="font-weight: normal"><BR>
</P>
<P>
<?php createCodeX("from math import atan, degrees\n\n source = nuke.toNode('Source').knobs()['translate'].getValue()\n offset = nuke.toNode('Source').knobs()['center'].getValue()\n aim = nuke.toNode('Aim').knobs()['translate'].getValue()\n deltaX,deltaY = source[0]+offset[0]-aim[0],source[1]+offset[1]-aim[1]\n\n try:\n
    angle = degrees(atan(deltaY/deltaX))\n except ZeroDivisionError:\n
    angle = 90 if deltaY&lt;=0 else 270\n finally:\n
    ret = angle - (180 if deltaX&lt;=0 else 0)");?>
</P>
<P STYLE="font-weight: normal"><BR>
</P>
<P>And voila&nbsp;!
Everything seems to work correctly with our two nodes, in order to go further in our exercise we're going to integrate all these nodes and attributes into a sole Group node, in other to have our own interface, managing the animated attributes and user interaction more easily. 
</P>
<P><BR>
<dt id="30"></dt><h1>Second part - Using a Group, adding attributes and making the user interface</h1>
<dt id="31"></dt><h2>Making the Group node</h2>
</P>
<P>We're going to create a new empty group (shortcut <B>Ctrl+G</B> without noting selected, or <B>Tab
</B>then type '<I>Group</I>'),
a new tab <B>Group1
Node Graph</B> should pop on the right of our <B>Node Graph</B>.</P>
<?php addImage("07.jpg", "Our new group and his tab");?>
<P>Inside this Group there is two nodes connected <B>Input1
</B>and 
<B>Output1</B>,
in a Group node, you're able to create any number of Input node (by hitting <B>Tab</B> then typing '<I>Input</I>',
or RMB &rarr; Other &rarr; Input) every one of these inputs will appear around your Group node in the <B>Node Graph</B>,
allowing users to connect new entries.
We'll just need one Input node for our exercise =).</P>

<?php addImage("02.jpg", "How the inside of your Group node should look");?>
<P><BR>
<dt id="32"></dt><h2>Feeding the Group</h2>
</P>
<P>Now create a Transform node and make it drop on the connection between <B>Input1
</B>and
<B>Output1</B>,
now that we've placed it correctly lets continue.</P>
<P>We now going to create a second Transform Node, which will be used to be the visible gizmo for the user, when he'll double-click on our group. All these attributes can be animated, but it won't be connected to anything because we just need it to 'wrap' the transform's datas made by user.</P>
<P>We can rename these two nodes <B>Aim_Transform&nbsp;</B>&raquo;
and &laquo;&nbsp;<B>Aim_Wrapper&nbsp;</B>&raquo;.</P>
<?php addImage("12.jpg", "Our two transforms, placed and named");?>
<dt id="33"></dt><h2>Group node's UI</h2>
<P>Now let's create the UI (<i>User Interface</i>) of our new group ! In order to do that, you need to RMB on the Group tab in the <b>Properties Bin</b></P>
<?php addImage("10.gif", "Adding attributes to our Group");?>
<P>Click on '<I>Manage User Knobs</I>',
in the newly opened window we're able to layout the attributes of our node that we want to access from outside of the Group node, so any user can manipulate the internal group's attributes globally. We are going to need four attributes ;

</P>
<P>	'<B>Source Position</B>'&nbsp;:
	the position of our node <B>Aim_Transform</B>,
which will move the object connected in input</P>
<P>	'<B>Aim Position</B>'&nbsp;		the target position of our transform</P>
<P>	'<B>Position Offset</B>'		the offset from the origin, representing the <I>center</I>'
attribute of our Transform</P>
<P>	'<B>Rotation Offset</B>'	allowing the user to set an additionnal rotation offset</P>
<dt id="34"></dt><h2>Making the Group node's UI</h2>
<P>There is two method to create attributes in this window, you can 'pick' existing an attribute which already exists inside the Group, or you can create a fresh & clean attribute, which you can use later, for our example we'll need both methods ;</P>

<OL>
	<LI><P>First click on <B>Add
	&rarr; Tab</B>
	and rename this new tab as you want, we're going to add our custom attributes inside.</P>
	<LI><P>Then click on <B>Pick...</B>,
	a new list will appear, containing different items, and there is our nodes inside the Group in the list ! Click on + in front of <B>Aim_Wrapper</B>
	then <B>Transform</B>
	and finally <B>translate</B>.</P>
	<P></P>
	<P>Rename this attribute by selecting it in the right list, then by clicking on <B>Edit</B>,
	in this window you can assign a new name to your attribute, and a hint (the attribute's label the user actually sees), type '<I>src</I>' in the <B>name</B> field and <I>Source Position</I> in the field <B>label</B>.</P>
	<P></P>
	<P>Valid your changes</P>
	<LI><P>Click again on <B>Add</B>
	&rarr; <B>2d Position Knob</B>
	and edit the properties of the newly created attribute, type '<I>aim</I>'
	in the field <B>name</B>,
	and '<I>Aim
	Position</I>'
	in the field <B>label</B>.</P>
	<LI><P>Now click on <B>Pick...</B>,
	and select <B>Aim_Wrapper</B>
	&rarr; <B>Transform</B> &rarr; <B>center</B>,
	rename it '<I>offset</I>'
	for the <B>name</B>,
	and '<I>Position
	Offset</I>'
	for the <B>label</B>.</P>
	<LI><P>Finally click (this is the last time, I swear) on <B>Pick...</B>,
	and select <B>Aim_Wrapper</B>
	&rarr; <B>Transform</B> &rarr; <B>rotate</B>,
	rename it to '<I>rotateOffset</I>'
	for the <B>nom</B>,
	and '<I>Rotation
	Offset</I>'
	for the <B>label</B>.</P>
</OL>
<?php addImage("08.jpg", "The four attributes we've added to our group");?>
<?php addImage("13.jpg", "The final appearance of our Group's UI, with some GroupBoxs");?>
<P>Now that our group is correctly set, we can start to work on connections inside !</P>

<dt id="35"></dt><h2>Adding connections</h2>
<P>Now lets connect the attribute <I>translate</I> and <I>center</I> of our <B>Aim_Wrapper</B> node to the <B>Aim_Transform</B> one ;</P>
<?php
addTip("There is two methods to make an instance copy of an attribute in Nuke ;
<P>- The first one, you can maintain the <B>Ctrl</B> key and LMB on the curve button on the right of you field's attribute, and drag & drop it on another curve button of a target attribute.</P>
<P>- The second way to do that is to <br><B>RMB</B> &rarr; <B>Copy</B> &rarr; <B>Copy Links</B> on the source's curve button, <br>then <B>RMB</B> &rarr; <B>Paste</B> &rarr; <B>Paste absolute</B> on the destination's curve button</P>
The destination attribute will be coloured with a light blue, and won't be editable anymore, this means their informations are overrided (you can see that visually in your <B>Node Graph</B> with the light green link between two nodes, and a half arrow indicating the transfer of datas.");
?>
<P>We just need to apply one of these two methods in order to make a instance between the <I>translate</I> and <I>center</I> of our <B>Aim_Wrapper</B> node to <B>Aim_Transform</B>.</P>
<?php addImage("00.gif", "Instance-copy <b>translate</b> and <b>center</b>");?>
<P></P>

<dt id="36"></dt><h2>Re-implementing our script</h2>
<P>Now that we've everything set with our Group and Transform nodes, we are going to modify the expression of the attribute '<I>rotate</I>'
of our node <B>Aim_Transform</B>,</P>
<P>The script writing-process will be slightly different because we're now working inside a group, so we just need to access to internal nodes of this Group.</P>
<P>The syntax to access to the attribute of a node inside a group can be achieved this way ;</P>
<P>
<?php createCodeX("nuke.thisParent().knobs()['src'].getValue()");?>
</P>
<P>This line will get the value of the attribute '<I>src</I>' of the Group, and you need to know that for the attributes which we have <I>picked</I> like <B>Source Position, Position Offset </B> and 
<B>Rotation Offset</B>,
we get the value of the node with this syntax ;</P>
<P>
<?php createCodeX("nuke.thisParent().knobs()['src'].getLinkedKnob().getValue()");?>
</P>
<P>Re-using our crazy script <?php what("previously written in the first part", "#24");?> and replacing the values' retrieval we get something like ; 
</P>
<P>
<?php createCodeX("from math import atan, degrees\n\n source = nuke.thisParent().knobs()['src'].getLinkedKnob().getValue()\n offset = nuke.thisParent().knobs()['offset'].getLinkedKnob().getValue()\n aim = nuke.thisParent().knobs()['aim'].getValue()\n deltaX,deltaY = source[0]+offset[0]-aim[0],source[1]+offset[1]-aim[1]\n offset = nuke.thisParent().knobs()['rotateOffset'].getLinkedKnob().getValue()\n\n try:\n\tangle = degrees(atan(deltaY/deltaX))\n except ZeroDivisionError:\n\tangle = 90 if deltaY&lt;=0 else 270\n finally:\n\tret = angle - (180 if deltaX&lt;=0 else 0) + offset","Expression of our Aim Node",True);?>
</FONT></P>
<dt id="37"></dt><h2>Ending of the second part</h2>
<P>We're done ! Our little Aim node is now achieved ! You can connect a simple shape on your Group's input to see the result ! When you double-click on your Group, you'll see two gizmos (or <i>manipulators</i>) in you Viewer, one for the <B>Aim_Wrapper</B> and another for the 2D position <B>aim</B>, symbolized by a dot.</P>
<?php addImage("05.jpg", "Our crazy group <b>Aim</b>");?>
<?php addImage("09.gif", "Final result", 400);?>
<P>Click on the link below to download the Nuke scene of the tutorial to test it by yourself ! Or the sole Nuke Node that you can Copy-Paste in your scene =).</P>
<?php 
	$_GET['n'] = 'nuke01';
	$_GET['buttons'] = true;
	include_once './dl/wrap/index.php';
?>

