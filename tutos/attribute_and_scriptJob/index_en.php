<?php include_once 't/head.php';?>
<dt id="10"></dt><h1>Introduction</h1>

<P>In this tutorial we're going to build a simple system based on <?php cmds('scriptNode');?> and 
<?php cmds('scriptJob');?>, with these two kind of nodes, we're going to be able to 
automate more complex things in our Maya scene !</P>
<P>This example will be very easy, we're just going to add an attribute on an object which allow 
the user to change the vertexColor value of the selected object !</P>
<P>The concept will be something like ;
</P>
<OL>
	<OL>
		<LI><P>Add a custom attribute to our object</P>
		<LI><P>Create a Python function which will act depending on our custom attribute's value</P>
		<LI><P>Connect this function using a <?php cmds('scriptJob');?> so this will be executed every time our 
		attribute is changed</P>
		<LI><P>Create a <?php cmds('scriptNode');?> which will contains all this stuff and execute when our scene is 
started, so it'll be in memory and ready-to-use =) !</P>
	</OL>
</OL>
<P>Let's go practice !</P>
<P>
<br>
<dt id="20"></dt><h1>First part ; preparing our scene & tools</h1>
<dt id="21"></dt><h2>Setting our custom attribute</h2>
</P>
<P>First we need to create an attribute, let's say on a sphere, we select our sphere, go in 
the <b>Channel Box</b> then <b>Edit &rarr; Add Attribute</b> and we create a new attribute named 
'<I>color</I>', we select the <I>Enum</I> kind, and we add three Elements (by clicking on a new 
empty line in the <I>Enum Names</I> list, this will add a new item), <B>Red</B>, <B>Green</B> and
<B>Blue</B></P>
<P><BR>
</P>
<?php addImage("00.gif", "the AddAttribute window");?>
<dt id="22"></dt><h2>Writing our function</h2>
<P>Then we're going to write a Python function, names <I>colorchange</I>, we're going to need one 
'special' <B>cmds</B> function, <?php cmds('polyColorPerVertex');?> which will allow us to replace and/or update 
the colorSet of our object to change the color globally. For this example we're going to consider 
that the target object, is the selected one when the function is executed, which is the most common 
case, but this should be changed for more advanced work, if you use an external command control 
panel for instance.</P>
<P>Our function should looks like something like this;</P>
<?php createCodeX("import maya.cmds as cmds

def colorChange():
	# we are changing the colorVertex info of our object depending of the value
	# of the 'color' attribute on the selected object
	obj_attr = '%s.color' % cmds.ls(sl=True)[0]
	if cmds.getAttr(obj_attr)==0 : # when the attribute is set to 'Blue'
		cmds.polyColorPerVertex(r=0.0,g=0.0,b=1.0,a=1,cdo=True)
			
	elif cmds.getAttr(obj_attr)==1 : # when the attribute is set to 'Red'
		cmds.polyColorPerVertex(r=1.0,g=0.0,b=0.0,a=1,cdo=True)
			
	elif cmds.getAttr(obj_attr)==2 : # when the attribute is set to 'Green'
		cmds.polyColorPerVertex(r=0.0,g=1.0,b=0.0,a=1,cdo=True)");
?>
<P>Okay that's it ! Our little function here, if you call it, with <B><I>colorChange()</I></B> will 
change the colorVertex info of our object depending of the value of it's '<I>color</I>' attribute !</P>

<dt id="30"></dt><h1>Second part ; creating and connecting the scriptJob & scriptNode</h1>
<dt id="31"></dt><h2>scriptJob</h2>
<P>The Python Maya syntax to create a <?php what('callback', 
'https://en.wikipedia.org/wiki/Callback_%28computer_programming%29');?> can be achieved using a 
<?php cmds('scriptJob');?> which will be then connected to an event, namely our fresh <i>colorChange</i> 
function !</P>

<?php createCodeX("cmds.scriptJob(attributeChange=['pSphere1.color',colorChange])");?>
<P>Executing this line of code above, will register a new callback in Maya, then every time you 
change the value of the attribute '<I>color</I>', the function '<I>colorChange</I>' will be called, 
simple and easy =)</P>
<dt id="32"></dt><h2>scriptNode</h2>
<P>The last step for us will be to write our famous '<I>colorChange</I>' function and the <?php 
cmds('scriptJob call','','scriptJob');?> in a string variable, we'll connect the whole thing to a <?php 
cmds('scriptNode');?>, so it's gonna be run everytime our scene is started ! </P>
<P STYLE="font-style: normal">The Maya Python syntax to do so is ;</P>
<?php createCodeX("cmds.scriptNode(st = 2, bs = myCode , n = 'sn_colorChange', stp = 'python')");?>
<p><br>The differents attributes we're going to use on the <?php cmds('scriptNode');?> are ;</p>
<?php
createCodeX("st = 2		 		 	  # type of execution (this one is 'when scene starts')
bs = myCode					# our string variable containing our function
n = 'sn_colorChange'	  	 # name of our node
stp = 'python'				# the type of our string function, 'python' or 'mel'");
?>
<br>
<?php
addNote("Here we have to notice two things, first, if you want to see you're freshly created node, in the 
<b>Outliner</b> you need to <B>RMB &rarr; Show DAG Objects</B>, this will display several nodes in 
the lists and you're scriptNode is here =).
<br><br>
The second thing is, if you want to write a multi-line variable in Python you need to declare 
the variable's content using triple-quotations ''', like that ;
");?>
<br>
<?php createCodeX("myCode = '''
multi
lines
code
'''","Multi-line code sample");?>

<P>Now we just going to copy and paste our whole function inside a our <B>myCode</B> variable, and 
create a <?php cmds('scriptJob');?> which contains our variable ! </P>

<dt id="33"></dt><h2>Ending & Assembly</h2>
<P>While the creation of our scriptNode, we're just going to replace our multi-line variable to 
make it read-able by Maya. By replacing the triple-quotations of our string by double !</P>
<P STYLE="font-style: normal; font-weight: normal">We get ;</P>
<?php
createCodeX("myCode = '''
import maya.cmds as cmds
def colorChange() :
	obj_attr = '%s.color' % cmds.ls(sl=True)[0]
	if cmds.getAttr(obj_attr)==0:
		cmds.polyColorPerVertex(r=0.0,g=0.0,b=1.0,a=1,cdo=True)
	elif cmds.getAttr(obj_attr)==1:
		cmds.polyColorPerVertex(r=1.0,g=0.0,b=0.0,a=1,cdo=True)
	elif cmds.getAttr(obj_attr)==2:
		cmds.polyColorPerVertex(r=0.0,g=1.0,b=0.0,a=1,cdo=True)
		
cmds.scriptJob(attributeChange=['pSphere1.color',colorChange])
'''

cmds.scriptNode( st=2, bs=myCode.replace(\"'''\",\"''\" ), n='sn_colorChange', stp='python')","scriptJob and scriptNode assembly",True);
?>
<br>
<P>Easy ! Run that and save your scene, open it again (in order to trigger our <b>scriptNode</b> 
when the scene starts), and you're sphere will gets it's color changed everytime you modify it's 
'<I>color</I>' attribute !<br>Here is a demo ;</P>
<?php addImage('demo.gif', 'scriptNode and scriptJob demo');?>
<p>You can also download the demo-scene, you'll see how it works, this is very easy !
<br><i>Don't forget to 
display your DAG objects if you want to find the scriptNode ;) !</i></p>
<?php 
	$_GET['n'] = 'scriptJob';
	$_GET['buttons'] = true;
	include_once './dl/wrap/index.php';
?>
