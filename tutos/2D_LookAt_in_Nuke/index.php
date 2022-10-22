<?php
	include_once 't/head.php';
?>


<font class='title'>Sommaire</font>
<a href="#10"><h1 class='sum' id='s0'>Concept</h1></a>
<a href="#20"><h1 class='sum' id='s1'>Première partie : Mise en pratique</h1></a>
<a href="#21"><h2 class='sum' id='s2'>Préparation des noeuds et utilisation des expressions</h2></a>
<a href="#22"><h2 class='sum' id='s3'>Fonctionnement des expressions</h2></a>
<a href="#23"><h2 class='sum' id='s4'>Quelques lignes de code</h2></a>
<a href="#24"><h2 class='sum' id='s5'>Conclusion de la premiére partie</h2></a>

<a href="#30"><h1 class='sum' id='s6'>Seconde partie - Utilisation d'un Group, ajouts d'attributs et interface utilisateur</h1></a>
<a href="#31"><h2 class='sum' id='s7'>Création d'un groupe</h2></a>
<a href="#32"><h2 class='sum' id='s8'>Remplissage du Group</h2></a>
<a href="#33"><h2 class='sum' id='s0'>Concept de l'interface du Group</h2></a>
<a href="#34"><h2 class='sum' id='s1'>Création de l'interface du Group</h2></a>
<a href="#35"><h2 class='sum' id='s2'>Ajout des connections</h2></a>
<a href="#36"><h2 class='sum' id='s3'>Ré-implémentation de notre script</h2></a>
<a href="#37"><h2 class='sum' id='s4'>Conclusion de la seconde partie</h2></a>
<br>
<dt id="10"></dt><h1>Concept</h1>
<P>Dans ce tutorial nous allons apprendre comment g&eacute;rer ses propres transformations sur des n&oelig;uds Nuke avec une petite mise en pratique ; cr&eacute;er un n&oelig;ud de Aim (ou LookAt) avec un n&oelig;ud de Transform.</P>

<P>Le concept est assez simple, nous allons r&eacute;cup&eacute;rer les positions respectives d'une source et d'un target et calculer l'angle entre ces deux positions via Python, gr&acirc;ce &agrave; l'outil des Expressions, nous allons pouvoir ins&eacute;rer ce petit script dans n'importe quel n&oelig;ud.</P>

<P>En faisant une rapide recherche sur la <A HREF="http://fr.wikipedia.org/wiki/Trigonom&eacute;trie">trigonom&eacute;trie</A>,
on apprend que&nbsp;;</P>
<font size=4 style='margin-left:60px'>tan &alpha; 
= A / B</font>
<P>et donc que</P>
<font size=4 style='margin-left:60px'>&alpha; = atan ( A / B ) 
</font>
<P>le calcul va donc &ecirc;tre tr&egrave;s rapide&nbsp;! Il suffit de calculer la diff&eacute;rence entre les positions de la source et du target, de diviser l'une par l'autre, d'y appliquer l'inverse de la tangente, le r&eacute;sultat &eacute;tant exprim&eacute; en radians.</P>

<dt id="20"></dt><h1>Première partie : Mise en pratique</h1>
<dt id="21"></dt><h2>Préparation des noeuds et utilisation des expressions</h2>
<P>Commen&ccedil;ons par cr&eacute;er un n&oelig;ud de Transform (raccourci <B>T</B>,
ou <B>Tab</B> puis tapez '<I>Transform</I>')</P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/06.jpg" NAME="images1" ALIGN=CENTER WIDTH=140 HEIGHT=87 BORDER=0>
<font class='alt'><br>Noeud de Transform</font></div><BR CLEAR=LEFT>
</P>
<P>dans 
l'attribut <B>rotate</B> du Transform, faites bouton-droit sur la petite ic&ocirc;ne &agrave;
droite du champ de texte</P>

<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/04.jpg" NAME="images2" ALIGN=CENTER WIDTH=580 HEIGHT=322 BORDER=0>
<font class='alt'><br>Ajout d'une expression dans l'attribut de rotation de notre Transform</font></div><BR CLEAR=LEFT>
</P>
<P>et 
cliquez sur '<I>Add expression</I>...'</P>
<dt id="22"></dt><h2>Fonctionnement des expressions</h2>

<P ALIGN=LEFT STYLE="widows: 1">Une fen&ecirc;tre va s'ouvrir, vous pouvez inscrire du code dans ce nouveau champ de texte qui sera interpr&eacute;t&eacute; en amont par Nuke, avant d'&ecirc;tre envoy&eacute; comme valeur &agrave;
l'attribut correspondant (<B>rotate
</B>dans notre cas), par d&eacute;faut le code tap&eacute; dans ce champ est en <A HREF="http://fr.wikipedia.org/wiki/Tool_Command_Language">TCL</A></P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/11.jpg" NAME="images3" ALIGN=CENTER WIDTH=514 HEIGHT=122 BORDER=0>
<font class='alt'><br>Fenétre d'ajout d'expression</font></div><BR CLEAR=LEFT>
</P>

<P>Trois boutons se trouvent &agrave; la droite du champ de texte,</P>
<table><tr><td><div class='Qt_Button'>&hellip;</div></td><td width=25>:</td><td> permet d'&eacute;largir le champ afin de le rendre multi-ligne</P>
</td></tr><tr><td><div class='Qt_Button'>Py</div></td><td>:</td><td> permet de changer l'interpr&eacute;teur du code tap&eacute; dans le champ, le code sera maintenant interpr&eacute;t&eacute; en Python</P>
</td></tr><tr><td><div class='Qt_Button'>R</div></td><td>:</td><td> l'interpr&eacute;teur attendra la pr&eacute;sence de la variable <B>ret</B> qui correspondra &agrave; la valeur &agrave; retourner, &eacute;quivalent
- en substance - &agrave; la fonction <B>return</B> en Python.</P></td></tr></table>

<P>Nous allons activer ces trois boutons, le premier nous donnera plus de visibilit&eacute;, le second nous permettra d'ins&eacute;rer du code et des proc&eacute;dures un peu plus complexes, le dernier nous permettra de contr&ocirc;ler le retour de notre code.</P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/01.jpg" NAME="images4" ALIGN=CENTER WIDTH=514 HEIGHT=144 BORDER=0>
<font class='alt'><br>Fenétre d'ajout d'expression, avec les trois boutons activés</font></div><BR CLEAR=LEFT>
</P>
<dt id="23"></dt><h2>Quelques lignes de code</h2>
<P>Nous allons tout d'abord importer les modules n&eacute;cessaires &agrave;
notre op&eacute;ration, &agrave; savoir <a href="https://docs.python.org/2/library/math.html" target="_blank" class="codePy"> math</a>,
qui contient les fonctions <B>atan</B> et <B>degrees</B>.</P>
<P STYLE="font-weight: normal">
<?php createCodeX("from math import atan, degrees");
?></P>
<P STYLE="font-weight: normal">l'acc&egrave;s aux fonctions internes &agrave; Nuke se fait gr&acirc;ce au module
<B>nuke</B>,
la syntaxe pour r&eacute;cup&eacute;rer la valeur d'un attribut est&nbsp;;</P>
<P STYLE="font-weight: normal">
<?php createCodeX("nuke.toNode('Transform').knobs()['translate'].getValue()");?>
</P>
<P>r&eacute;cup&egrave;rera l'information de l'attribut '<I>translate</I>'
du n&oelig;ud nomm&eacute; '<B>Transform</B>'
et nous retournera donc <B>[
0.0 , 0.0 ]</B></P>
<P>correspondant
&agrave; translate X et translate Y</P>
<P>cette seule fonction va nous permettre d'&eacute;laborer notre script&nbsp;;
les lignes suivantes vont nous permettre de r&eacute;cup&eacute;rer les attributs de position de nos deux transforms, pr&eacute;alablement cr&eacute;&eacute;s, l'un &eacute;tant nomm&eacute; '<I><B>Source</B></I>'
et l'autre '<I><B>Aim</B></I>'.</P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/03.jpg" NAME="images5" ALIGN=CENTER WIDTH=278 HEIGHT=125 BORDER=0>
<font class='alt'><br>Nos deux noeuds de Transform que nous allons utiliser</font></div><BR CLEAR=LEFT>
</P>
<P>
<?php createCodeX("from math import atan, degrees\n\n source = nuke.toNode('Source').knobs()['translate'].getValue()\n aim = nuke.toNode('Aim').knobs()['translate'].getValue()\n");?>
</P>

<P>Soustrayons maintenant les parties de ces arrays,</P>
<P>
<?php createCodeX("deltaX,deltaY = source[0]-aim[0],source[1]-aim[1]");?>
</P>

<P>Et nous pouvons r&eacute;cup&eacute;rer l'angle entre ces deux points gr&acirc;ce &agrave; la formule&nbsp;:</P>
<P>
<?php createCodeX("angle = degrees(atan(deltaY/deltaX))");?>
</P>

<P>Le tout compil&eacute; en rempla&ccedil;ant la variable <B>angle</B> par la variable <B>ret</B>,
n&eacute;cessaire au retour de notre bout de code, cela donne&nbsp;;</P>
<P>
<?php createCodeX("from math import atan, degrees\n\n source = nuke.toNode('Source').knobs()['translate'].getValue()\n aim = nuke.toNode('Aim').knobs()['translate'].getValue()\n deltaX,deltaY = source[0]-aim[0],source[1]-aim[1]\n\n ret = degrees(atan(deltaY/deltaX))");
?>
</P>

<P>si l'on essaie maintenant de bouger le transform '<I><B>Aim</B></I>',
le gizmo du n&oelig;ud '<I><B>Source</B></I>'
s'orientera en direction de l'autre.</P>
<P>En l'&eacute;tat ce code n'est pas suffisant, en effet, certains cas ne sont pas &eacute;valu&eacute;s avec ces quelques lignes, tout d'abord le cas o&ugrave; deltaX = 0, ce qui aboutit &agrave; une exception
<a href="https://docs.python.org/2/library/exceptions.html#exceptions.ZeroDivisionError" target="_blank" class="codePy">ZeroDivisionError</a>,
et ensuite les deux cas de possibilit&eacute; des changements de signes de '<I>deltaX</I>'
et '<I>deltaY</I>',
avec ces trois cas de figures &eacute;lud&eacute;s et l'usage d'un
<I>try</I>/<I>except</I>/<I>finally</I> nous obtenons&nbsp;:</P>

<P>
<?php createCodeX("from math import atan, degrees\n\n source = nuke.toNode('Source').knobs()['translate'].getValue()\n aim = nuke.toNode('Aim').knobs()['translate'].getValue()\n deltaX,deltaY = source[0]-aim[0],source[1]-aim[1]\n\n try:\n
    angle = degrees(atan(deltaY/deltaX))\n except ZeroDivisionError:\n
    angle = 90 if deltaY&lt;=0 else 270\n finally:\n
    ret = angle - (180 if deltaX&lt;=0 else 0)");?>
</P>
<P STYLE="font-weight: normal"><BR>
</P>
<dt id="24"></dt><h2>Conclusion de la premiére partie</h2>
<P STYLE="font-weight: normal">D&eacute;sormais notre n&oelig;ud s'oriente correctement quelque que soit la position de l'un ou de l'autre&nbsp;!</P>
<P STYLE="font-weight: normal">La derni&egrave;re chose &agrave; avoir &agrave; g&eacute;rer, est l'attribut '<I>center</I>' de notre n&oelig;ud de Transform '<I><B>Source</B></I>',
pour cela il va simplement nous falloir pr&eacute;alablement additionner &agrave; l'attribut '<I>translate</I>'
de notre n&oelig;ud, l'attribut '<I>center</I>',
afin que l'utilisateur soit &agrave; m&ecirc;me de placer pr&eacute;alablement son n&oelig;ud de transform au bon endroit,
voire de l'animer.</P>
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
<P>Voila&nbsp;!
Tout fonctionne correctement avec nos deux n&oelig;uds, pour aller plus loin dans l'exercice nous allons maintenant int&eacute;grer ces diff&eacute;rents attributs dans un n&oelig;ud de Group, afin de pouvoir avoir notre propre interface, d'y g&eacute;rer les &eacute;l&eacute;ments animables ainsi que l'interaction avec l'utilisateur. 
</P>
<P><BR>
<dt id="30"></dt><h1>Seconde partie - Utilisation d'un Group, ajouts d'attributs et interface utilisateur</h1>
<dt id="31"></dt><h2>Création d'un groupe</h2>
</P>
<P>Pour cela cr&eacute;ons un groupe vide (raccourci <B>Ctrl+G</B> en n'ayant rien de s&eacute;lectionn&eacute;, ou <B>Tab
</B>puis tapez '<I>Group</I>'),
un nouvel onglet <B>Group1
Node Graph</B>
&agrave; c&ocirc;t&eacute; de notre <B>Node Graph</B> est apparu.</P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/07.jpg" NAME="images6" ALIGN=CENTER WIDTH=261 HEIGHT=255 BORDER=0>
<font class='alt'><br>Notre nouveau Group, et son onglet</font></div><BR CLEAR=LEFT>
</P>
<P>Dedans
2 n&oelig;uds sont pr&eacute;sents et reli&eacute;s l'un &agrave;
l'autre, <B>Input1
</B>et
<B>Output1</B>,
dans un n&oelig;ud de Group, vous allez pouvoir cr&eacute;er autant d'inputs que vous le souhaitez (en appuyant sur <B>Tab</B> puis tapez '<I>Input</I>',
ou bouton-droit &rarr; Other &rarr; Input) chacun de ces inputs apparaitra autour de votre n&oelig;ud de Group dans le <B>Node Graph</B>,
permettant aux utilsateurs d'y connecter de nouvelles entr&eacute;es.
Nous n'allons en avoir besoin que d'une pour notre exercice.</P>

<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/02.jpg" NAME="images7" ALIGN=CENTER WIDTH=194 HEIGHT=199 BORDER=0>
<font class='alt'><br>Apparence de l'intérieur du Group é sa création</font></div><BR CLEAR=LEFT>
</P>
<P><BR>
<dt id="32"></dt><h2>Remplissage du Group</h2>
</P>
<P>Cr&eacute;ez un n&oelig;ud de Transform dans notre group et faites glisser le n&oelig;ud sur la connexion entre <B>Input1
</B>et
<B>Output1</B>,
le voil&agrave; maintenant intercal&eacute; entre l'entr&eacute;e et la sortie du groupe.</P>
<P>Nous allons maintenant cr&eacute;er un second n&oelig;ud de Transform qui aura pour utilit&eacute; d'&ecirc;tre le gizmo visible quand l'utilisateur double-cliquera sur le groupe, tous ces attributs seront animables mais il ne sera pas connect&eacute; &agrave; aucune entr&eacute;e ou sortie de notre group, il sera simplement l&agrave;
en guise de 'wrapper', pour r&eacute;cup&eacute;rer les manipulations de l'utilisateur.</P>
<P>Nous pouvons renommer ces deux groupes comme suit&nbsp;; &laquo;&nbsp;<B>Aim_Transform&nbsp;</B>&raquo;
et &laquo;&nbsp;<B>Aim_Wrapper&nbsp;</B>&raquo;.</P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/12.jpg" NAME="images8" ALIGN=CENTER WIDTH=249 HEIGHT=164 BORDER=0>
<font class='alt'><br>Nos deux Transforms dans le groupe, connectés</font></div><BR CLEAR=LEFT>
</P>
<dt id="33"></dt><h2>Concept de l'interface du Group</h2>
<P>Cr&eacute;ons maintenant l'interface de notre nouveau groupe&nbsp;! Pour ce faire faites un bouton-droit sur l'onglet du group dans la Properties Bin</P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/10.gif" NAME="images9" ALIGN=CENTER WIDTH=312 HEIGHT=236 BORDER=0>
<font class='alt'><br>Ajout d'attributs sur notre groupe</font></div><BR CLEAR=LEFT>
</P>
<P>Cliquez sur '<I>Manage User Knobs</I>',
dans la nouvelle fen&ecirc;tre qui s'ouvre nous allons pouvoir mettre en place les champs que vous voulons que l'utilisateur puisse manipuler. Quatre attributs vont &ecirc;tre n&eacute;cessaires&nbsp;;

</P>
<P>	'<B>Source Position</B>'&nbsp;:
	la position de notre n&oelig;ud de transform <B>Aim_Transform</B>,
qui permettra de bouger l'objet</P>
<P>	'<B>Aim Position</B>'&nbsp;		la position de la cible de notre transformation</P>
<P>	'<B>Position Offset</B>'		le d&eacute;calage de la position &agrave; l'origine, repr&eacute;sentant l'attribut '<I>center</I>'
de notre transform</P>
<P>	'<B>Rotation Offset</B>'	permettant 
&agrave; l'utilisateur de rajouter une information de rotation additionnelle</P>
<dt id="34"></dt><h2>Création de l'interface du Group</h2>
<P>Il y a deux fa&ccedil;ons de cr&eacute;er des attributs dans cette fen&ecirc;tre, soit en 'pickant' un attributs d&eacute;j&agrave;
existant &agrave; l'int&eacute;rieur du group, soit en cr&eacute;ant un nouvel attribut vierge, qui sera r&eacute;cup&eacute;rer ou connecter plus tard, pour notre exemple nous allons avoir besoin de ces deux m&eacute;thodes pour cr&eacute;er un attributs&nbsp;;</P>

<OL>
	<LI><P>Tout
	d'abord cliquez sur <B>Add
	&rarr; Tab</B>
	et renommez ce nouvel onglet comme bon vous semble, nous allons
	rajouter nos attributs &agrave; l'int&eacute;reur de cet onglet.</P>
	<LI><P>Cliquez
	ensuite sur <B>Pick...</B>,
	une nouvelle liste apparaitra, contenant diff&eacute;rents objets,
	dont les deux n&oelig;uds que nous avons pr&eacute;alablement cr&eacute;er.
	Cliquer sur le + de <B>Aim_Wrapper</B>
	puis <B>Transform</B>
	et enfin <B>translate</B>.</P>
	<P></P>
	<P>Renommez
	cet attributs en le s&eacute;lectionnant dans la liste de gauche,
	puis en cliquant sur <B>Edit</B>,
	dans cette fen&ecirc;tre vous allez pouvoir assigner un nom &agrave;
	votre attributs, ainsi qu'un intitul&eacute; (qui sera visible par
	l'utilisateur), tapez '<I>src</I>'
	dans le champ du <B>nom</B>,
	et '<I>Source
	Position</I>'
	dans le champ<B>
	label</B>.</P>
	<P></P>
	<P>Validez
	par OK</P>
	<LI><P>Cliquez
	&agrave; nouveau sur <B>Add
	&rarr; 2d Position Knob</B>
	et &eacute;ditez ces propri&eacute;t&eacute;s, tapez '<I>aim</I>'
	dans le champ du <B>nom</B>,
	et '<I>Aim
	Position</I>'
	dans le champ <B>label</B>.</P>
	<LI><P>Cliquez
	maintenant sur <B>Pick...</B>,
	et s&eacute;lectionnez <B>Aim_Wrapper
	&rarr; Transform &rarr; center</B>,
	renommez le '<I>offset</I>'
	pour le <B>nom</B>,
	et '<I>Position
	Offset</I>'
	pour le <B>label</B>.</P>
	<LI><P>Enfin
	cliquez de nouveau sur <B>Pick...</B>,
	et s&eacute;lectionnez <B>Aim_Wrapper
	&rarr; Transform &rarr; rotate</B>,
	renommez le '<I>rotateOffset</I>'
	pour le <B>nom</B>,
	et '<I>Rotation
	Offset</I>'
	pour le <B>label</B>.</P>
</OL>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/08.jpg" NAME="images10" ALIGN=CENTER WIDTH=434 HEIGHT=264 BORDER=0>
<font class='alt'><br>Les quatre attributs que nous avons ajoutés é notre groupe</font></div><BR CLEAR=LEFT>
<div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/13.jpg" NAME="images10" ALIGN=CENTER BORDER=0>
<font class='alt'><br>L'apparence de notre groupe, avec quelques ajouts de GroupBox</font></div><BR CLEAR=LEFT>
</P>
<P>Notre groupe correctement configur&eacute; nous allons maintenant pouvoir travailler nos connections &agrave; l'int&eacute;rieur de ce dernier&nbsp;!</P>

<dt id="35"></dt><h2>Ajout des connections</h2>
<P>Connectons maintenant l'attribut translate et center du <B>Aim_Wrapper</B> sur <B>Aim_Transform</B>,
pour pouvoir copier en instance un attribut sur un autre, <I>en laissant enfonc&eacute; la touche Control </I>cliquez sur le petit bouton &agrave; droite du champ de l'attribut et faites le glisser sur le m&ecirc;me petit bouton de l'attribut cible.</P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/00.jpg" NAME="images11" ALIGN=CENTER WIDTH=371 HEIGHT=569 BORDER=0>
<font class='alt'><br>Copie en instance des attributs <b>translate</b> et <b>center</b></font></div><BR CLEAR=LEFT>
</P>
<P>Les pistes de destinations deviendront bleu clair et ne serons plus manipulables &agrave; la main, indiquant qu'elles re&ccedil;oivent d&eacute;j&agrave; des informations (la symbolisation de ce lien est visible dans le Node Graph, par un lien vert clair entre les deux n&oelig;uds, et une demi-fl&egrave;che signifiant la direction de l'&eacute;change d'information.</P>

<dt id="36"></dt><h2>Ré-implémentation de notre script</h2>
<P>Nous allons maintenant modifier l'expression de l'attribut '<I>rotate</I>'
de notre n&oelig;ud <B>Aim_Transform</B>,</P>
<P>L'&eacute;criture de notre script va &ecirc;tre l&eacute;g&egrave;rement diff&eacute;rente car nous travaillons d&eacute;sormais &agrave; l'int&eacute;rieur d'un groupe, ce qui va nous permettre d'acc&eacute;der directement aux n&oelig;uds d&eacute;pendants de ce groupe.</P>
<P>La syntaxe pour pouvoir acc&eacute;der &agrave; l'attribut d'un n&oelig;ud
&agrave; l'int&eacute;rieur d'un groupe se fait comme suit&nbsp;;</P>
<P>
<?php createCodeX("nuke.thisParent().knobs()['src'].getValue()");?>
</P>
<P>R&eacute;cuperera la valeur de l'attribute 'src' du groupe, &agrave; savoir que pour les attributs <I>pick&eacute;s</I> comme <B>Source Position, Position Offset </B>et
<B>Rotation Offset</B>,
la r&eacute;cup&eacute;ration de la valeur de fait de mani&egrave;re l&eacute;g&egrave;rement diff&eacute;rente&nbsp;;</P>
<P>
<?php createCodeX("nuke.thisParent().knobs()['src'].getLinkedKnob().getValue()");?>
</P>
<P>En reprenant notre code &eacute;crit pr&eacute;c&eacute;demment et en rempla&ccedil;ant la r&eacute;cup&eacute;ration des valeurs on obtient&nbsp;; 
</P>
<P>
<?php createCodeX("from math import atan, degrees\n\n source = nuke.thisParent().knobs()['src'].getLinkedKnob().getValue()\n offset = nuke.thisParent().knobs()['offset'].getLinkedKnob().getValue()\n aim = nuke.thisParent().knobs()['aim'].getValue()\n deltaX,deltaY = source[0]+offset[0]-aim[0],source[1]+offset[1]-aim[1]\n offset = nuke.thisParent().knobs()['rotateOffset'].getLinkedKnob().getValue()\n\n try:\n\tangle = degrees(atan(deltaY/deltaX))\n except ZeroDivisionError:\n\tangle = 90 if deltaY&lt;=0 else 270\n finally:\n\tret = angle - (180 if deltaX&lt;=0 else 0) + offset","Expression du noeud d'Aim",True);?>
</FONT></P>
<dt id="37"></dt><h2>Conclusion de la seconde partie</h2>
<P>Et voil&agrave;&nbsp;! Notre petit n&oelig;ud d'aim est maintenant achev&eacute;&nbsp;! Vous pouvez connecter un simple Roto &agrave;
l'input du group afin de voir r&eacute;sultat&nbsp;! Votre groupe poss&egrave;de deux gizmos manipulables dans le Viewer, le gizmo du Transform <B>Aim_Wrapper</B> et la position 2D <B>aim</B> symbolis&eacute;e par un point.</P>
<P><div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/05.jpg" NAME="images12" ALIGN=CENTER WIDTH=161 HEIGHT=101 BORDER=0>
<font class='alt'><br>Notre groupe <b>Aim</b></font></div><BR CLEAR=LEFT>
<div align='center' class='content'><img class='content' SRC="<?php $current_dir();?>img/09.gif" NAME="images13" ALIGN=CENTER WIDTH=303 HEIGHT=251 BORDER=0>
<font class='alt'><br>Mise en application du noeud</font></div><BR CLEAR=LEFT>
</P>
<P>Cliquez sur ce lien pour t&eacute;l&eacute;charger le script nuke de ce groupe afin de l'&eacute;prouver par vous-m&ecirc;me, ou la sc&egrave;ne d'exemple pour voir la mise en pratique&nbsp;!</P>
<?php 
	$_GET['n'] = 'nuke01';
	$_GET['buttons'] = true;
	include_once './dl/wrap/index.php';
?>

