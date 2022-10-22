<?php include_once 't/head.php';?>
<dt id="10"></dt><h1>Introduction</h1>

<P>Dans ce tutorial nous allons voir comment mettre en place un petit
syst&egrave;me &agrave; base de <?php cmds('scriptNode');?> et de <?php cmds('scriptJob');?>, nous
permettant d'&eacute;xecuter des fonctions un peu plus complexe
lorsqu'on manipulera notre sc&egrave;ne.</P>
<P>Cet exemple sera tr&egrave;s simple&nbsp;; nous allons simplement
rajouter un attribut &agrave; notre objet qui permettra &agrave;
l'utilisateur de choisir la couleur de l'objet s&eacute;lectionn&eacute;&nbsp;!</P>
<P>Le concept sera donc&nbsp;; 
</P>
<OL>
	<OL>
		<LI><P>Rajouter un attribut <i>'custom'</i> sur notre objet</P>
		<LI><P>Cr&eacute;er une fonction qui r&eacute;agira selon la
		valeur de notre attribut</P>
		<LI><P>Connecter cette fonction &agrave; un <?php cmds('scriptJob');?> afin qu'elle
		s'&eacute;xecute &agrave; chaque changement de notre attribut</P>
		<LI><P>Cr&eacute;er un <?php cmds('scriptNode');?> qui contiendra cette ensemble et
		l'&eacute;xecutera &agrave; chaque lancement de Maya</P>
	</OL>
</OL>
<P>Passons &agrave; la mise en pratique&nbsp;!</P>
<P>
<br>
<dt id="20"></dt><h1>Première partie : Pr&eacute;parer notre sc&egrave;ne</h1>
<dt id="21"></dt><h2>Cr&eacute;ation de l'attribut sur un nouvel objet</h2>
</P>
<P>Tout d'abord cr&eacute;ons notre attribut sur une b&ecirc;te
sph&egrave;re, on s&eacute;lectionne la sph&egrave;re, puis dans la
Channel Box on va dans <b>Edit &rarr; Add Attribute</b> et on cr&eacute;e un
nouvel attribut '<I>color</I><SPAN STYLE="font-style: normal">' avec
trois Enums (vous pouvez ajouter de nouveau éléments à la liste <I>Enum Names</I> en sélectionnnant 
une nouvelle ligne vide), </SPAN><SPAN STYLE="font-style: normal"><B>Red</B></SPAN><SPAN STYLE="font-style: normal">,
</SPAN><SPAN STYLE="font-style: normal"><B>Green</B></SPAN><SPAN STYLE="font-style: normal">,
</SPAN><SPAN STYLE="font-style: normal"><B>Blue</B></SPAN></P>
<P><BR>
</P>
<?php addImage("00.gif", "Fen&ecirc;tre d'AddAttribute");?>
<dt id="22"></dt><h2>&Eacute;criture de la fonction</h2>
<P>Ensuite nous &eacute;crivons notre fonction <I>colorChange</I><SPAN STYLE="font-style: normal">,
nous allons avoir besoin d'une fonction 'spéciale' <B>cmds</B>, à savoir </SPAN>
<?php cmds('polyColorByVertex');?>
(qui nous permettra de remplacer le colorSet de notre objet s'il
existe ou d'en cr&eacute;er un nouveau le cas &eacute;ch&eacute;ant).
Pour cette exemple nous partons du principe que l'objet est
s&eacute;lectionn&eacute; lorsque la fonction sera ex&eacute;cut&eacute;e.</SPAN></P>
<P STYLE="font-style: normal; font-weight: normal">La fonction
devrait donc ressembler &agrave;&nbsp;;</P>
<?php createCodeX("import maya.cmds as cmds

def colorChange() :
	obj_attr = '%s.color' % cmds.ls(sl=True)[0] # on recupere le nom de l'objet
	if cmds.getAttr(obj_attr)==0 : # quand l'attribut est a 'Blue'
		cmds.polyColorPerVertex(r=0.0,g=0.0,b=1.0,a=1,cdo=True)
			
	elif cmds.getAttr(obj_attr)==1 : # quand l'attribut est a 'Red'
		cmds.polyColorPerVertex(r=1.0,g=0.0,b=0.0,a=1,cdo=True)
			
	elif cmds.getAttr(obj_attr)==2 : # quand l'attribut est a 'Green'
		cmds.polyColorPerVertex(r=0.0,g=1.0,b=0.0,a=1,cdo=True)
		
");?>
<P STYLE="font-style: normal"><BR>
</P>
<P><SPAN STYLE="font-style: normal">Et voil&agrave;&nbsp;! Cette
petite fonction, si vous l'appelez avec colorChange(), changera la
couleur de notre objet selon la valeur de l'attribut '</SPAN><I>color</I><SPAN STYLE="font-style: normal">'&nbsp;!</SPAN></P>

<dt id="30"></dt><h1>Seconde partie : Cr&eacute;ation et connection du scriptJob et du scriptNode</h1>
<dt id="31"></dt><h2>scriptJob</h2>
<P><SPAN STYLE="font-style: normal">La syntaxe Python Maya pour cr&eacute;er
un </SPAN><?php what('callback', 
'https://en.wikipedia.org/wiki/Callback_%28computer_programming%29');?><SPAN STYLE="font-style: normal"> se fait
via un <?php cmds('scriptJob');?>, dans lequel vous allez pouvoir connecter &agrave;
un event, notre nouvelle fonction </SPAN><I>colorChange&nbsp;</I><SPAN STYLE="font-style: normal">!</SPAN></P>
<?php createCodeX("cmds.scriptJob(attributeChange=['pSphere1.color',colorChange])");?>
<P STYLE="font-style: normal">Vous noterez que le nom de l'objet doit
&ecirc;tre inscrit en entier&nbsp;!</P>

<P STYLE="font-style: normal">Ex&eacute;cutez donc cette ligne de
code et d&eacute;sormais, &agrave; chaque fois que vous changerez
votre attribut, la couleur changera automatiquement.</P>
<P STYLE="font-style: normal"><BR>
</P>
<dt id="32"></dt><h2>scriptNode</h2>
<P STYLE="font-style: normal">La derni&egrave;re &eacute;tape
consiste &agrave; mettre cette fonction ainsi que le <?php cmds('scriptJob');?> dans
une nouvelle variable string, qu'on connecte &agrave; un scriptNode
afin qu'il soit automatiquement ex&eacute;cut&eacute; &agrave; chaque
lancement de la sc&egrave;ne&nbsp;!</P>
<P STYLE="font-style: normal">La syntaxe Python Maya pour ce faire
est la suivante&nbsp;;</P>
<?php createCodeX("cmds.scriptNode(st = 2, bs = monCode , n = 'sn_colorChange', stp = 'python')");?>
<p><br>Les diff&eacute;rents attributs du <?php cmds('scriptNode');?> que nous utilisons sont;</p>
<?php createCodeX("st = 2		  	  	# type d'execution du script
bs = monCode			# notre variable string contenant la fonction a executer
n = 'sn_colorChange'	# le nom de notre scriptNode
stp = 'python'		   # le type de script execute, 'python' ou 'mel'");?>
</P>
<br>
<?php
addNote("Ici nous devons faire attention à deux choses, la première est que si vous désirez voir
le noeud que nous venons de créer dans l'<B>Outliner</B>, il faut <B>RMB &rarr; Show DAG Objects</B>
, cela affichera de nombreux nouveaux noeuds dans votre Outliner, dont le <?php cmds('scriptNode');?> précédemment
créé =).
<br><br>
La seconde chose à savoir est que pour écrire une variable multi-lignes en Python il faut utiliser
des triples-quotations ''', comme ceci ;
");?>
<br>
<?php createCodeX("monCode = '''
code
multi
lignes
'''","Exemple de code multi-lignes");?>

<P><SPAN STYLE="font-style: normal">Nous allons donc tout simplement
copier tout le code de la fonction </SPAN><I>colorChange</I><SPAN STYLE="font-style: normal">
dans la variable </SPAN><SPAN STYLE="font-style: normal"><B>monCode</B></SPAN><SPAN STYLE="font-style: normal">,
en ajoutant notre <?php cmds('scriptJob');?> &agrave; la fin&nbsp;!</SPAN></P><br>
<dt id="33"></dt><h2>Conclusion & Assemblage de l'ensemble</h2>
<P STYLE="font-style: normal; font-weight: normal">Lors de la
cr&eacute;ation du scriptNode, nous allons devoir faire un replace
sur notre variable string multi-lignes afin de le rendre lisible par
Maya.</P>
<P STYLE="font-style: normal; font-weight: normal">Ce qui donne&nbsp;;</P>
<?php
createCodeX("monCode = '''
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

cmds.scriptNode( st=2, bs=monCode.replace(\"'''\",\"''\" ), n='sn_colorChange', stp='python')","Fonction réactive au changement de l'attribut color & scriptNode",True);
?>
<P STYLE="font-style: normal; font-weight: normal"><BR>La petite astuce sera juste de remplacer les triples-quotations par des doubles afin que le code soit interpr&eacute;t&eacute; correctement par Python.
</P>
<P STYLE="font-style: normal; font-weight: normal">Tout simplement&nbsp;!</P>
<P><SPAN STYLE="font-style: normal">Ex&eacute;cutez
ceci et enregistrez votre sc&egrave;ne, r&eacute;-ouvrez la (afin que
le <?php cmds('scriptNode');?> s'ex&eacute;cute) et votre sph&egrave;re changera de
couleur &agrave; chaque changement de son attribut '</SPAN><I><SPAN STYLE="font-weight: normal">color</SPAN></I><SPAN STYLE="font-style: normal">'&nbsp;!</SPAN></P>
<P>Voici ce que vous devriez obtenir ;</P>
<?php addImage('demo.gif', 'Mise en pratique de notre scriptJob & scriptNode');?>
<P>Et vous pouvez aussi télécharger la scène de démonstration pour l'essayer de vous même !<br>
<i>N'oubliez pas d'afficher les DAG objects dans votre <B>Outliner</B> si vous voulez trouver le 
noeud de scriptNode =) !
<?php 
	$_GET['n'] = 'scriptJob';
	$_GET['buttons'] = true;
	include_once './dl/wrap/index.php';
?>

