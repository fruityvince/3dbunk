<?php
	include_once 't/head.php';
?>

<font class='title'>Sommaire</font>
<a href="#10"><h1 class='sum' id='s0'>Concept</h1></a>
<a href="#20"><h1 class='sum' id='s1'>R&eacute;cup&eacute;ration des channels</h1></a>
<a href="#21"><h2 class='sum' id='s2'>Premi&egrave;re approche 'du li&egrave;vre'</h2></a>
<a href="#22"><h2 class='sum' id='s3'>Seconde approche 'de la tortue'</h2></a>
<a href="#30"><h1 class='sum' id='s6'>Cr&eacute;ation des n&oelig;uds</h1></a>
<a href="#40"><h1 class='sum' id='s6'>R&eacute;glages visuels</h1></a>
<a href="#50"><h1 class='sum' id='s6'>Assemblage final</h1></a>
<a href="#60"><h1 class='sum' id='s6'>La cerise sur le g&acirc;teau</h1></a>
<br>
<dt id="10"></dt><h1>Introduction</h1>
<P>Dans ce petit tutorial nous allons
aborder le scripting sous Nuke via le Script Editor, qui permet
d'ex&eacute;cuter des scripts plus ou moins complexes ponctuellement,
contrairement aux diff&eacute;rentes opportunit&eacute;s qu'offre
Nuke d'&eacute;xecuter du script Python 'en direct' pendant
l'&eacute;valuation des frames (cf. le tutorial sur la <a href='http://localhost/3dbunk/index.php?p=t&t=2D_LookAt_in_Nuke' target='_blank'>cr&eacute;ation d'un node de 'Lookat' sous Nuke</a>).</P>

<P>Voici notre mission du jour, et ses objectifs ; 

<OL>
	<LI><P>d&eacute;tecter les diff&eacute;rentes
	couches (channels) contenues dans une image
	<LI><P>extraire ces diff&eacute;rentes
	couches via des n&oelig;uds Nuke cr&eacute;&eacute;s &agrave; la
	vol&eacute;e
	<LI><P>mettre en place un layout dans le
	Node View et rendre le tout visuellement viable
</OL>
<P>Je met &agrave; votre disposition une petite image EXR afin que vous puissiez mettre en pratique ce que nous allons voir sur un fichier contenant divers channels</P>
<center><a href='<?php $current_dir();?>files/bruce_0000.exr'>DOWNLOAD</a></center><BR><BR>

<P>Commen&ccedil;ons par r&eacute;cup&eacute;rer
le n&oelig;ud actuellement s&eacute;lectionn&eacute; par
l'utilisateur, et ce gr&acirc;ce &agrave; la fonction <B>selectedNode</B>()
dans la librairie <B>nuke&nbsp;</B>;</P>
<?php
createCodeX("node = nuke.selectedNode()");
?>
<BR><BR>

<dt id="20"></dt><h1>R&eacute;cup&eacute;ration des channels</h1>
<dt id="21"></dt><h2>Premi&egrave;re approche 'du li&egrave;vre'</h2>
<P>La premi&egrave;re technique que nous a transmis notre vénérable Ma&icirc;tre est la technique dite 'du Li&egrave;vre', &agrave; savoir aller &agrave; l'objectif le plus rapidement et l&eacute;g&egrave;rement possible, sans se formaliser sur la forme.
<BR>Pour ce faire nous allons boucler &agrave;
travers l'attribut <B>channels</B>() de notre n&oelig;ud afin d'en
r&eacute;cuperer les diff&eacute;rents channels. Nous allons stocker
le tout dans un <a href="https://docs.python.org/2/library/stdtypes.html#set" target="_blank" class="codePy">set</a>, afin que cela reste le plus l&eacute;ger
possible, &agrave; noter que les sets en python <a href="https://docs.python.org/2/library/sets.html" target="_blank" class="codePy">n'ayant pas d'index</a>,
ils ne maintiennent pas l'ordre de cr&eacute;ation. L'avantage &eacute;tant
la l&eacute;geret&eacute;, et le fait que chaque &eacute;l&eacute;ment
du set soit unique, ce qui supprime automatiquement les doublons,
pratique pour ce que nous comptons faire.
Voyons donc ce que nous retourne
<B>node.channels</B>()&nbsp;;
</P>
<?php
createCodeX("node = nuke.selectedNode()
print node.channels()
# Result : ['rgba.red', 'rgba.green', 'rgba.blue', 'rgba.alpha', 'lighting.blue', 'lighting.green', 'lighting.red', 'reflectionFilter.blue', 'reflectionFilter.green', 'reflectionFilter.red', 'specular.blue', 'specular.green', 'specular.red']
");
?>
<BR>
<P>On voit donc que l'on r&eacute;cup&egrave;re
une liste de tout les channels avec les couches R, G et B s&eacute;par&eacute;es,
nous comptons conserver chaque channel dans son ensemble (&agrave;
savoir avec les 3 couches R,G et B), nous allons donc diviser les
&eacute;l&eacute;ments pour ne r&eacute;cuperer que ce qui se trouve
avant le point, ce qui nous donne quelque chose comme ; 
</P>
<?php
createCodeX("# assignation multiples de variables en une ligne, gain d'esthetique et de place =)
node, chans = nuke.selectedNode(), set()
for chan in node.channels():
	chans.add(chan.split('.')[0]) # add etant l'equivalent du append pour un set
print chans
# Result : set(['specular', 'lighting', 'reflectionFilter', 'rgba'])");
?>
<BR>

<P>Nous r&eacute;cup&eacute;rons maintenant un
set propre ne contenant que des &eacute;l&eacute;ments uniques en
deux lignes, bien que nous en ayons perdus l'ordre.</P>
<BR>

<dt id="22"></dt><h2>Seconde approche 'de la tortue'</h2>
<P>La seconde technique du Grand Ma&icirc;tre, j'ai nomm&eacute; la technique de la Tortue, selon les besoins ou les pr&eacute;f&eacute;rences
de chacun il peut &ecirc;tre utile de r&eacute;cuperer la liste des
channels dans l'ordre contenu par le fichier, pour ce faire nous
allons simplement utiliser une liste, ce qui nous donne un code un
peu plus long&nbsp;;</P>

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

<P>On r&eacute;cup&egrave;re donc la m&ecirc;me
chose mais dans l'ordre =), nul besoin de s'&eacute;taler sur le
sujet, la magie de programmation &eacute;tant qu'il y a un millier de
chemins diff&eacute;rents qui m&egrave;nent au m&ecirc;me r&eacute;sultat&nbsp;!
Il faut juste trouver le plus court =)</P>
<BR><BR>

<dt id="30"></dt><h1>Cr&eacute;ation des n&oelig;uds</h1><P>Nous allons maintenant attaquer le gros
du script, &agrave; savoir cr&eacute;er un ensemble de n&oelig;uds
<I>Shuffle</I> / <I>Remove</I> / <I>Dot</I> pour pouvoir extraire
chaque channel et en retourner un output propre&nbsp;!
Le n&oelig;ud de <I>Shuffle</I> va nous
permettre de r&eacute;cup&eacute;rer le channel que nous voulons et
de l'envoyer en RGB, nous allons ensuite utiliser le n&oelig;ud de
<I>Remove</I> en mode '<I><B>keep</B></I>' pour supprimer tout les
autres channels, afin que la lecture de notre node soit rapide pour
le reste de l'arbre et qu'il n'ait pas &agrave; &eacute;changer un
trop grand nombre d'informations.</P>
<P>La derni&egrave;re chose, par confort
visuel, sera l'ajout d'un n&oelig;ud <I>Dot</I>, en activant l'option
hide_input, ce qui nous permettra de dupliquer pour aller le placer
o&ugrave; l'on veut dans notre arbre Nuke, ce qui peut &ecirc;tre
fort pratique pour pouvoir lire plus ais&eacute;ment un arbre
complexe&nbsp;!</P>
<P>Comme &ccedil;a&nbsp;;</P>
<div align='center' class='content'><IMG class='content' SRC="<?php $current_dir();?>img/00.png" NAME="graphics1" WIDTH=201 HEIGHT=376 BORDER=0>
<font class='alt'><br>Aper&ccedil;u de notre objectif</font></div><BR CLEAR=LEFT>

<P>Avec un petit effet couleur sur notre
n&oelig;ud de <I>Dot</I> afin de les rep&eacute;rer facilement =)
La cr&eacute;ation de n&oelig;ud avec
la librairie <B>nuke</B> se g&egrave;re en acc&egrave;dant au module
<B>nodes</B>. Aucun param&egrave;tre n'est n&eacute;cessaire &agrave;
la cr&eacute;ation d'un n&oelig;ud sous Nuke, nous allons n&eacute;anmoins
utiliser les param&egrave;tres <I><B>name</B></I> et <I><B>inputs</B></I>
afin de sp&eacute;cifier le nom et la connexion en entr&eacute;e de
notre n&oelig;ud.</P>
<P>Si nous retransposons &ccedil;a en
script nous devrions avoir&nbsp;; </P>
<?php
createCodeX("node = nuke.selectedNodes()
shuffle_node = nuke.nodes.Shuffle(name='SHUFFLE', inputs=[node])");
?>
<BR><P>
En ex&eacute;cutant ces deux petites
lignes avec un n&oelig;ud de s&eacute;lectionn&eacute; vous devriez
voir un nouveau n&oelig;ud de <I>Shuffle</I> se cr&eacute;er en
dessous de votre n&oelig;ud.</P>
<P>
<table><tr><td><img src='img/tips.jpg'></td><td><I>
<u>Tips</u> :
La petite astuce pour acc&egrave;der &agrave;
tout les attributs modifiables de votre n&oelig;ud Nuke, (ces
attributs se nomment des <I>knobs</I>), il suffit de laisser la
souris au dessus d'un champ &eacute;ditable de votre n&oelig;ud, et
une tooltip s'ouvrira avec &agrave; la premi&egrave;re ligne le nom
pour acc&egrave;der &agrave; cet attribut&nbsp;;</td></tr></table></P>
<div align='center' class='content'><IMG class='content' SRC="<?php $current_dir();?>img/01.gif" NAME="graphics2" WIDTH=434 HEIGHT=294 BORDER=0>
<font class='alt'><br>Comment conna&icirc;tre le nom de nos knobs</font></div><BR CLEAR=LEFT>

<P>Ce qui est fort pratique pour nous&nbsp;!
Nous n'avons plus qu'&agrave; faire nos courses, choisir les <I>knobs</I>
que nous voulons modifier / &eacute;diter et les int&eacute;grer &agrave;
notre code&nbsp;! 

La modification d'un attribut d'un n&oelig;ud
nuke se fait tr&egrave;s simplement en acc&egrave;dant au <I>knob</I>
de cette fa&ccedil;on&nbsp;;</P>
<?php
createCodeX("node = nuke.selectedNodes()
# Creation du noeud de Shuffle
shuffle_node = nuke.nodes.Shuffle(name='SHUFFLE', inputs=[node])
shuffle_node['in'].setValue('rgba') # definit l'attribut 'in' avec la valeur 'rgba'");
?>
<BR><P>
A savoir que pour des attributs simples
comme ceux que nous avons &agrave; modifier, nous n'aurons que besoin
de valeurs comme des strings, des booleans, et des floats.
<BR>

Appliquons maintenant cette
m&eacute;thodologie pour un ensemble de n&oelig;uds, ce qui devrait
nous donner&nbsp;;</P>
<?php
createCodeX("node = nuke.selectedNodes()
# Creation du noeud de Shuffle
shuffle_node = nuke.nodes.Shuffle(name='SHUFFLE', inputs=[node])
shuffle_node['in'].setValue('rgba') # definit l'attribut 'in' avec la valeur 'rgba'
# Creation du noeud de Remove
remove_node = nuke.nodes.Remove(name='REMOVE', inputs=[shuffle_node])
remove_node['operation'].setValue('keep')
# on definit notre remove pour garder un channel
remove_node['channels'].setValue('rgba')
# on garde donc les couches 'rgba'
remove_node['postage_stamp'].setValue(True)
# definit l'attribut 'postage_stamp' a True
# Creation du noeud de Dot
dot_node = nuke.nodes.Dot(name='DOT', inputs=[remove_node])
dot_node['label'].setValue('rgba') # on definit un label pour notre Dot");
?>
<BR><P>
<table><tr><td><img src='img/note.jpg'></td><td><I>
<u>Note</u>&nbsp;; L'attribut '<B>postage_stamp</B><SPAN STYLE="font-weight: normal">'</SPAN>
indique &agrave; Nuke que ce node affichera un petit aper&ccedil;u de
ce qu'il contient, comme le n&oelig;ud de <I>Read</I>, ce qui nous
permet visuellement de nous y retrouver parmis les nombreux  channels
que nous allons extraire =)&nbsp;!</I></td></tr></table></P><P>
Lan&ccedil;ons ce petit bout de script
avec un n&oelig;ud de s&eacute;lectionn&eacute;, nous devrions
r&eacute;cuperer ce que nous avons dans la capture d'&eacute;cran
ci-dessous, sans les attributs visuels et les '<B>hide_input</B><SPAN STYLE="font-weight: normal">'</SPAN>.
Nous allons y venir ne vous en faites pas&nbsp;!</P>
<BR>

<dt id="40"></dt><h1>R&eacute;glages visuels</h1><P>
Nous allons maintenant r&eacute;gler
les quelques d&eacute;tails visuels, vous allez voir que certains
probl&egrave;mes peuvent survenir. Par exemple, lors de l'assignation
de l'attribut '<B>hide_input</B><SPAN STYLE="font-weight: normal">'</SPAN>
&agrave; True, Nuke semble avoir quelques probl&egrave;me pour
maintenir un layout harmonieux, nous allons devoir y rem&eacute;dier
en pla&ccedil;ant nous-m&ecirc;me les n&oelig;uds dont nous cachons
l'input.
<BR>

Tout d'abord r&eacute;cup&eacute;rons
la position de notre n&oelig;ud s&eacute;lectionn&eacute;, pour ce
faire, utilisons les fonctions <B>xpos</B>() et <B>ypos</B>() du
n&oelig;ud, donc&nbsp;;</P>
<?php
createCodeX("node = nuke.selectedNode()
print node.xpos(), node.ypos()
# Result : 172");
?>
<BR>
<P>
Ainsi nous r&eacute;cup&eacute;rons la
valeur en absisse de notre node, il ne nous reste plus qu'&agrave;
faire un petit produit en croix pour conna&icirc;tre le point de
d&eacute;part selon le nombre de channel que nous avons, avec la
formule suivante les nodes seront centr&eacute; en dessous du node de
<I>Read</I>, n'h&eacute;sitez pas &agrave; r&eacute;-adapter &agrave;
votre go&ucirc;t =)&nbsp;!</P>
<?php
createCodeX("node = nuke.selectedNode()
x, y = node.xpos() - (len(node.channels) - 1) * 50, node.ypos() + 100");
?><BR><P>
Nous allons pouvoir placer notre n&oelig;ud
gr&acirc;ce aux <I>knobs</I> <B>xpos</B> et <B>ypos</B> de notre
n&oelig;ud, du genre&nbsp;;<BR><B>node['xpos'].setValue(172)</B>
<BR>

Passons donc &agrave; la compilation
finale de nos petits bouts de scripts et int&eacute;grons le tout
dans une loop pour les diff&eacute;rents channels =)&nbsp;!
</P>

<BR>

<dt id="50"></dt><h1>Assemblage final</h1><P>
Maintenant que nous avons jet&eacute;
un coup d'oeil et d&eacute;bloqu&eacute; tout ce qui pouvait nous
poser probl&egrave;me pour la r&eacute;solution de notre mission du
jour, nous allons pouvoir compiler tout ce beau monde dans un script,
qui suivra le sch&eacute;ma suivant&nbsp;;</P>
<UL>
	<LI>on r&eacute;cup&egrave;re la
	s&eacute;lection
	<LI>on r&eacute;cup&egrave;re les
	channels de notre n&oelig;ud
	<LI>on r&eacute;cup&egrave;re la
	position de notre n&oelig;ud
	<LI>on boucle &agrave; travers tout
	les channels&nbsp;;
	<UL>
		<LI>on cr&eacute;e un n&oelig;ud de
		<I>Shuffle</I> que l'on place &agrave; la bonne position sous
		notre n&oelig;ud
		<LI>on cr&eacute;e un n&oelig;ud de
		<I>Remove</I>
		<LI>on cr&eacute;e un n&oelig;ud de
		<I>Dot</I> que l'on remplace sous le n&oelig;ud de <I>Remove</I>
		(si l'on choisit d'opter pour une activation de l'attribut
		<B>hide_input</B>, ce qui nous permettra de placer le dot o&ugrave;
		l'on veut dans notre arbre, sans &ecirc;tre emb&ecirc;t&eacute;
		par une longue droite connect&eacute;e au n&oelig;ud de <I>Remove</I>
		<LI>on applique une couleur au label
		du n&oelig;ud <I>Dot</I> avec la technique dite 'du pauvre'
	</UL>
</UL>
<BR>

<P>Tr&egrave;s bien&nbsp;! Voici donc un
premier jet de cet assemblage final,</P>
<?php
createCodeX("# Recuperation de la selection
node, chans = nuke.selectedNode(), set()
# Recuperation des channels de notre noeud
for chan in node.channels():
	chans.add(chan.split('.')[0]) # add etant l'equivalent du append pour un set
# Recuperation de la position du noeud
x, y = node.xpos() - (len(chans) - 1) * 50, node.ypos() + 100
# On boucle a travers les channels
for i, chan in enumerate(chans):
	# Creation du noeud de Shuffle
	shuffle_node = nuke.nodes.Shuffle(name='SHF_%s' % chan, inputs=[node])
	shuffle_node['in'].setValue(chan) # definit l'attribut 'in' avec la valeur 'rgba'
	shuffle_node['postage_stamp'].setValue(True) # definit l'attribut 'postage_stamp' a True
	shuffle_node['hide_input'].setValue(True)
	# Placement correct de notre node
	shuffle_node['xpos'].setValue(x)
	shuffle_node['ypos'].setValue(y)
	x += 100 # on incremente notre valeur de x pour que le prochain node soit bien place
	# Creation du noeud de Remove
	remove_node = nuke.nodes.Remove(name='REM_%s' % chan, inputs=[shuffle_node])
	remove_node['operation'].setValue('keep') # on definit notre remove en mode keep
	remove_node['channels'].setValue('rgb') # on garde donc les couches 'rgba'
	# Creation du noeud de Dot
	dot_node = nuke.nodes.Dot(name='DOT_%s' % chan, inputs=[remove_node])
	dot_node['label'].setValue(chan) # on definit un label pour notre Dot
	dot_node['hide_input'].setValue(True)
	# Placement de notre Dot par rapport au Shuffle
	dot_node['xpos'].setValue(shuffle_node.xpos() + 35)
	dot_node['ypos'].setValue(shuffle_node.ypos() + 100)
	# La fameuse technique 'du pauvre'
	dot_node['note_font_color'].setValue(4286779903)");
?>
<BR>



<P>Voila, une premi&egrave;re mouture
ex&eacute;cutable de notre script nous permettra, de g&eacute;n&eacute;rer
en un '<B>Ctrl+Enter</B>' tout nos n&oelig;uds pour extraire les
channels de notre fichier, si jamais vous manquez de source, vous
pouvez utiliser le fichier fournis au d&eacute;but du tutorial&nbsp;!
Ce qui nous donne&nbsp;;</P>
<BR>


<div align='center' class='content'><IMG class='content' SRC="<?php $current_dir();?>img/02.png" NAME="graphics3" WIDTH=444 HEIGHT=270 BORDER=0>
<font class='alt'><br>Ce qu'on obtient avec l'image d'exemple</font></div><BR CLEAR=LEFT>

<P>Revenons, si vous le voulez bien sur la
technique 'du pauvre' qui consiste, certains parmis vous l'auront
compris, &agrave; d&eacute;finir la couleur &agrave; la main pour
ensuite print cette valeur, et la copier / coller b&ecirc;tement.
M&eacute;thode simple, et efficace pour du cas par cas&nbsp;:)&nbsp;!
<BR>

Notre petit script semble bien
fonctionner&nbsp;! Je suggererai simplement d'y ajouter un nommage
plus correct, rendez-vous &agrave; la prochaine &eacute;tape!</P>
<BR>

<dt id="60"></dt><h1>La cerise sur le g&acirc;teau</h1><P>
En r&eacute;cup&eacute;rant par exemple
le nom du <I>Read</I> auquel sont connect&eacute;s les <I>Dots</I>,
pour cela nous allons utiliser une libraire qui peut &ecirc;tre tr&egrave;s
pratique dans Nuke, ainsi qu'un gain de temps consid&eacute;rable, &agrave;
savoir la librairie <B>nuke.tcl</B></P><P>
Nous pouvons utiliser cette librairie
pour &eacute;valuer des expressions TCL, qui est le langage interne &agrave;
Nuke. <A HREF="http://www.nukepedia.com/reference/Tcl/group__tcl__builtin.html">Voici
la doc</A> o&ugrave; vous pouvez faire vos courses, selon vos besoin
=), &agrave; savoir que ces expressions sont utilisables dans tout
les champs de Nuke et &eacute;valu&eacute; &agrave; chaque
rafraichissement.
L'expression que nous allons utiliser
est le topnode, permettant de remonter en haut d'une hi&eacute;rarchie
de node, et donc de nous ramener au premier n&oelig;ud, qui sera
selon toute probabilit&eacute; un <I>Read</I>, si ce n'est pas le
cas, je vous encourage &agrave; ajouter un pr&eacute;fixe de nommage
par vous-m&ecirc;me&nbsp;!
<BR><P>
En s&eacute;lectionnant un n&oelig;ud
de notre hi&eacute;rarchie et en ex&eacute;cutant cette petite ligne
de code</P>
<?php
createCodeX("print nuke.tcl('knob [topnode %s].file' % nuke.selectedNode().name())
# Result : .../files/bruce_0000.exr");
?>
<BR>
<P>Nuke nous retournera la valeur du
<I>knob</I> <B>file</B> du <B>topnode</B> de notre hi&eacute;rarchie,
m&ecirc;me si nous optons pour un arbre o&ugrave; quelques
modifications sont faites sur le n&oelig;ud de <I>Read</I> avant
d'ex&eacute;cuter notre script, l'&eacute;valuation nous retournera
la valeur correcte du <I>knob</I> <B>file&nbsp;</B>! Comme par
exemple&nbsp;; </P>

<div align='center' class='content'><IMG class='content' SRC="<?php $current_dir();?>img/03.jpg" NAME="graphics4" WIDTH=155 HEIGHT=197 BORDER=0>
<font class='alt'><br>Même en s&eacute;lectionnant un n&oelig;d enfant, le script trouvera le Read</font></div><BR CLEAR=LEFT>

<P>En s&eacute;lectionnant le n&oelig;ud
de <I>Transform</I> et en ex&eacute;cutant notre petite ligne de
script, le r&eacute;sultat sera le m&ecirc;me, nonobstant le n&oelig;ud
s&eacute;lectionn&eacute; =)&nbsp;!
<BR>

Si nous int&eacute;grons ce petit ajout
&agrave; notre script d&eacute;j&agrave; &eacute;crit, en
l'impl&eacute;mentant dans le nom de chaque n&oelig;ud que nous
cr&eacute;ons, nous &eacute;viterons ainsi les doublons de nommage
dans notre arbre Nuke.
Voici &agrave; quoi devrait ressembler
notre script final&nbsp;:</P>
<?php
createCodeX("# Recuperation de la selection
node, chans = nuke.selectedNode(), set()
# Recuperation du filename correct
filename = nuke.tcl('knob [topnode %s].file' % node.name())
# On trim notre filename pour en retourner un nom plus sympathique
nicename = '_'.join(filename.split('/')[-1].split('_')[:-1])
# Recuperation des channels de notre noeud
for chan in node.channels():
	chans.add(chan.split('.')[0]) # add etant l'equivalent du append pour un set
# Recuperation de la position du noeud
x, y = node.xpos() - (len(chans) - 1) * 50, node.ypos() + 100
# On boucle a travers les channels
for i, chan in enumerate(chans):
	# On definit un nom de base utilise sur tout nos nodes
	base_name = '%s_%s' % (nicename, chan)
	# Creation du noeud de Shuffle
	shuffle_node = nuke.nodes.Shuffle(name='SHF_%s' % base_name, inputs=[node])
	shuffle_node['in'].setValue(chan) # definit l'attribut 'in' avec la valeur 'rgba'
	shuffle_node['postage_stamp'].setValue(True) # definit l'attribut 'postage_stamp' a True
	shuffle_node['hide_input'].setValue(True)
	# Placement correct de notre node
	shuffle_node['xpos'].setValue(x)
	shuffle_node['ypos'].setValue(y)
	x += 100 # on incremente notre valeur de x pour que le prochain node soit bien place
	# Creation du noeud de Remove
	remove_node = nuke.nodes.Remove(name='REM_%s' % base_name, inputs=[shuffle_node])
	remove_node['operation'].setValue('keep') # on definit notre remove en mode keep
	remove_node['channels'].setValue('rgb') # on garde donc les couches 'rgba'
	# Creation du noeud de Dot
	dot_node = nuke.nodes.Dot(name='DOT_%s' % base_name, inputs=[remove_node])
	dot_node['label'].setValue(chan) # on definit un label pour notre Dot
	dot_node['hide_input'].setValue(True)
	# Placement de notre Dot par rapport au Shuffle
	dot_node['xpos'].setValue(shuffle_node.xpos() + 35)
	dot_node['ypos'].setValue(shuffle_node.ypos() + 100)
	# La fameuse technique 'du pauvre'
	dot_node['note_font_color'].setValue(4286779903)","Notre script final d'extraction");
?>
<BR>

<P>Et son r&eacute;sultat&nbsp;;</P>
<BR>

<div align='center' class='content'><IMG class='content' SRC="<?php $current_dir();?>img/04.jpg" NAME="graphics5" WIDTH=467 HEIGHT=410 BORDER=0>
<font class='alt'><br>Résultat final</font></div><BR CLEAR=LEFT>

<P>Nous r&eacute;cup&eacute;rons
maintenant un nom que nous utilisons pour nommer nos n&oelig;uds, ce
qui rend le tout un peu plus propre et viable en production. 

<BR><BR>Certaines &eacute;tapes suppl&eacute;mentaires
pourraient &ecirc;tre envisag&eacute;es comme la consid&eacute;ration
de cas de figure o&ugrave; la s&eacute;lection est mauvaise, ou la
gestion d'erreurs avec des <B>try / except</B>, mais nous en
resterons l&agrave; pour cet exemple =)</P>