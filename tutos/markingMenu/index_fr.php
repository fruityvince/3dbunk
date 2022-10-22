<?php include_once 't/head.php';?>
<dt id="10"></dt><h1>Qu'est-ce que le Marking Menu ?</h1>
<p>Bonne question, le marking menu c'est le menu radial contextuel qui apparait lorsque l'on fait un clic droit sur un objet dans le viewport de Maya ;</p>
<?php addImage("whatis_hotbox.jpg", "Le marking menu")?> 
<p>Elle est compos&eacute;e de 8 <?php cmds("menuItem")?>, qui peuvent &ecirc;tre eux-m&ecirc;mes compos&eacute;s de sous-menu, les positions de ces menus sont appel&eacute;s les 
<?php cmds("positions radiales", "radialPosition", "menuItem")?>, nomm&eacute;es selon les points cardinaux</p>
<?php addImage("hotbox_radials.jpg", "Les noms des positions radials")?>

<dt id="20"></dt><h1>D&eacute;finir le besoin</h1>
<p>Il est clair que pour ce qui est de la customisation, on peut aller tr&egrave;s loin dans Maya, except&eacute; certaines propri&eacute;t&eacute;s un peu statiques presque tout y 
est modifiable. Une chose demeure n&eacute;anmoins assez rigide, ce sont les marking menus, vous pouvez en &eacute;ditez certains via les pr&eacute;f&eacute;rences,
mais &ccedil;a reste tr&egrave;s simple, qui d&eacute;pendent de la s&eacute;lection et qui font gagner un temps fou quand on 
sait s'en servir convenablement.</p>
<p>Nous allons donc essayer (avec succ&egrave;s :)) dans ce tutorial de changer un peu cette propri&eacute;t&eacute; statique, afin de pouvoir l'&eacute;diter et la modifier selon nos besoins,
par exemple pour ;
<ul>
	<li>un rigger : modifier le marking menu afin de permettre de cr&eacute;er les contraintes d'un clic, si la s&eacute;lection est un joint il pourrait avoir directement
	acc&egrave;s &agrave; la cr&eacute;ation de l'IK</li>
	<li>un animateur : donner la possibilit&eacute; &agrave; l'animateur, de switcher entre IK / FK le controlleur s&eacute;lectionner, si celui-ci a l'attribut correspondant, reset
	les transformations en translation, en rotation, ou les deux</li>
	<li>la phase de rendu : cr&eacute;er un light set en un clic, dupliquer le shader de l'objet s&eacute;lectionn&eacute;</li>
	<li>etc...</li>
</ul>
<p>T&acirc;chons donc de trouver comment Maya rempli ce menu et comment nous pourrions le modifier...</p>

<dt id="30"></dt><h1>L'enqu&ecirc;te</h1>
<dt id="31"></dt><h2>Les pr&eacute;misses</h2>

<p>Il va sans dire que les premi&egrave;res recherches sur la question ont &eacute;t&eacute; bien plus laborieuses... Evitons l'enqu&ecirc;te &agrave; la Derrick, voyons grand, voyons Les Experts
&agrave; Miami !</p>
<p>Tr&egrave;s bien, si l'on ouvre notre tr&egrave;s prolixe ami le <b>scriptEditor</b> et si l'on active l'option <b>History</b>&rarr;<b>Echo All Commands</b>. L'utilisation du 
clic droit de la souris dans le 'vide' (le vide n'existe pas, tout le monde sait &ccedil;a !) devrait nous afficher une ligne comme &ccedil;a ;</p>
<?php createMELCodeX("buildObjectMenuItemsNow \"MayaWindow|<i>(...)</i>|modelPanel4|modelPanel4ObjectPop\";")?>
<p>Cr&eacute;ons un cercle et r&eacute;essayons ;</p>
<?php createMELCodeX("buildObjectMenuItemsNow \"MayaWindow|<i>(...)</i>|modelPanel4|modelPanel4ObjectPop\";
dagMenuProc(\"MayaWindow|<i>(...)</i>|modelPanel4|modelPanel4ObjectPop\", \"nurbsCircle1\");")?>
<p>Tiens tiens... Voil&agrave; qui est interessant, l'affichage <u>contextuel</u> du menu appelle une seconde fonction qui se trouve &ecirc;tre la proc&eacute;dure <b>dagMenuProc</b>, essayons
donc de la trouver en utilisant notre second ami un peu trop curieux, <?php dl("Notepad ++", "https://notepad-plus-plus.org/download")?>, en recherchant dans le 
dossier de Maya l'appel de la fonction <b>dagMenuProc</b> vous devriez vite tomber sur un fichier contenant un grand nombre de fois ce mot, j'ai nomm&eacute; le fichier
<i>dagMenuProc.mel</i>, on peut pas faire plus clair =). Vous trouverez ce fichier dans <u>Dossier_Maya/scripts/others/dagMenuProc.mel</u>, ouvrons-le... Bon, c'est un
gros fichier !</p>

<dt id="32"></dt><h2>On a trouv&eacute; le coupable</h2>
<p>Maintenant que nous avons rep&eacute;r&eacute; le bon fichier, baladons-nous dedans pour voir si une fonction pourrait &ecirc;tre la fonction principale de ce dagMenuProc... Au hasard,
la plus grand fonction, j'ai nomm&eacute; <b>createSelectMenuItems</b>. C'est l'objet de notre qu&ecirc;te, la fonction appel&eacute;e par Maya lors d'un bouton droit, on remarquera
d'ailleurs que cette fonction attend 2 arguments <i>$parent</i> et <i>$item</i>, qui sont les deux arguments envoy&eacute;s par Maya dans le code cit&eacute; plus haut.</p>
<p>Lisons un peu ce que cette fonction contient, il faut admettre que le MEL n'est pas le code le plus lisible au monde... Si nous devions faire un r&eacute;sum&eacute; de la
fonction cela donnerait ;</p>
<blockquote><b>proc createSelectMenuItems(<i>$parent, $item</i>)</b><br>
&emsp;&emsp;- D&eacute;claration des variables<br>
&emsp;&emsp;- On regarde le type du premier &eacute;l&eacute;ment <i>$item</i>, et on d&eacute;finit la variable correspondantes &agrave; <b>True</b><br>
&emsp;&emsp;- Condition selon le type de la s&eacute;lection<br>
&emsp;&emsp;&emsp;&emsp;On remplit notre menu en g&eacute;n&eacute;rant des <b>menuItem</b> pour chaque position du marking menu<br>
&emsp;&emsp;- On parente notre nouveau menu &agrave; <i>$parent</i>
</blockquote>

<dt id="40"></dt><h1>Un peu de code</h1>
<dt id="41"></dt><h2>Bonjour, je suis un hackeur professionnel</h2>
<p>Tr&egrave;s bien, maintenant que nous savons quelle est la fonction recherch&eacute;e, nous allons tout simplement essayer de la red&eacute;clarer &agrave; Maya, ouvrons donc notre 
<b>scriptEditor</b> et cr&eacute;ons-y un nouvel onglet <i>MEL</i>, nous allons commencer par un test b&ecirc;te et m&eacute;chant ; &agrave; savoir red&eacute;clarer une fonction vide :</p>
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent, string \$item){}")?>
<?php addImage("empty_proc.gif", "Overrider le marking menu")?>
<p>T&acirc;chons de faire un peu mieux que &ccedil;a... Par exemple ajoutons un menu en haut. Si l'on regarde la fonction originale, on voit qu'un bouton est d&eacute;clar&eacute; de
la mani&egrave;re suivante ; </p>
<?php createMELCodeX("menuItem -label \$enableIkHandle
    -annotation (uiRes(\"m_dagMenuProc.kEnableIKHandleAnnot\"))
    -echoCommand true
    -c (\"ikHandle -e -eh \" + \$handle)
    -rp \$radialPosition[4];")?>
<p>on voit aussi qu'&agrave; la toute fin, la fonction <b>setParent -menu $parent;</b> est appel&eacute;e, ce qui parente notre menu cr&eacute;&eacute; &agrave; l'interface de Maya.</p>
<p>Essayons donc de red&eacute;finir la fonction <b>createSelectMenuItems</b> comme suit ;</p>
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent, string \$item){
    menuItem -label \"test\" -radialPosition \"N\";
    setParent -menu \$parent;
}")?>
<p>Tr&egrave;s bien ! Voil&agrave; appara&icirc;tre notre premier menu !</p>
<?php addImage("first_menu.jpg", "Notre premier menu")?>
<p>Gr&acirc;ce &agrave; l'argument <i>-command</i> ou simplement <i>-c</i> nous allons pouvoir ajouter une
commande &agrave; notre fonction, qui sera execut&eacute;e lorsque le menu sera s&eacute;lectionn&eacute; ;</p> 
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent, string \$item){
    menuItem -label \"test\" -radialPosition \"N\"
		-c \"print \\\"Hello world\\\"\";
    setParent -menu \$parent;
}", "", true)?><br>
<?php addTip("Tr&egrave;s g&eacute;n&eacute;ralement, voire toujours, dans les languages de programmation l'usage de l'antislash \\ permet de 'passer' un caract&egrave;re, ce qui 
signifie qu'il ne sera pas interpr&eacute;t&eacute; par le langage de programmation, dans l'exemple ci-dessus, l'usage de <b>\\\"</b> nous as permis d'ins&eacute;rer <u>&agrave; l'int&eacute;rieur</u>
de notre string un second bloc de text avec des double guillemets.<br>
Le premier sera interpr&eacute;t&eacute; par l'int&eacute;pr&eacute;teur MEL et le second sera interpr&eacute;t&eacute; &agrave; l'ex&eacute;cution de la commande")?>

<dt id="42"></dt><h2>Personnalisation avanc&eacute;e</h2>

<p>Finalement, si l'on j&egrave;te un coup d'oeil &agrave; la documentation du <?php cmds("menuItem")?> on voit que l'on peut aller plus loin que ce que Maya
nous montre, par exmple avez-vous d&eacute;j&agrave; vu un item du marking menu en italique ? Rien de plus simple !</p>  
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent, string \$item){
    menuItem -label \"test\" -radialPosition \"N\"
		-c \"print \\\"Hello world\\\"\";
    menuItem -label \"menu en italique\" -radialPosition \"E\"
		-c \"delete `ls -sl`\"
		-itl ;
    setParent -menu \$parent;
}", "", true)?>
<p>Et le tour est jou&eacute; =)</p>
<?php addImage("italic_menu_fr.jpg")?>
<dt id="43"></dt><h2>Sous menus</h2>
<p>L'impl&eacute;mentation d'un sous menu est elle aussi assez simple, il suffit de d&eacute;clarer &agrave; <b>True</b> le flag <i>subMenu</i> puis de parenter nos sous menus
au menu parent (celui pour qui on a activ&eacute; le flag <i>subMenu</i>). Un exemple tr&egrave;s simple serait ;</p>
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent , string \$item ){
    menuItem -label \"test\" -radialPosition \"N\"
        -c \"print \\\"Hello world\\\"\";
        
	menuItem
		-label \"menu principal\" -radialPosition \"W\" 		
		-subMenu 1;

    	menuItem
    		-label \"Premier sous menu\" -radialPosition \"N\"
    		-command \"print \\\"Premier\\\";\";
    	menuItem
    		-label \"Second sous menu\" -radialPosition \"W\"
    		-command \"warning \\\"Second\\\";\";
    	setParent -menu ..;		
    		
    setParent -menu \$parent ;
}")?><br>
<?php addNote("La fonction <b>setParent -menu ..</b> va parenter nos menus au premier 'pr&eacute;tendant' rencontr&eacute; en remontant l'ordre de cr&eacute;ation, &agrave; savoir
notre menu 'menu principal' !");?>
<p>Ce qui donne ;</p>
<?php addImage("sub_menu_fr.gif")?>

<dt id="44"></dt><h2>Contextualisation</h2>
<p>Notre syst&egrave;me semble fonctionner. N&eacute;anmoins cette m&eacute;thode override <u>tout</u> les marking menus quelquesoit l'objet s&eacute;lectionn&eacute;, pour ne modifier le menu que
pour un type d'objet, il nous faut conserver la d&eacute;claration des variables du type <b>$isBezierObject</b> au d&eacute;but de la proc&eacute;dure MEL ainsi que la condition 
assignant la valeur 1 &agrave; la bonne variable ;</p>
<?php createMELCodeX("if (1 <= size(\$maskList)) {
    \$isLatticeObject = (\$maskList[0] == \"latticePoint\");
    \$isJointObject = (\$maskList[0] == \"joint\");
    \$isHikEffector = (\$maskList[0] == \"hikEffector\");
    \$isIkHandleObject = (\$maskList[0] == \"ikHandle\");
    \$isParticleObject = (\$maskList[0] == \"particle\");
    \$isSpringObject = (\$maskList[0] == \"springComponent\");
    \$isSubdivObject = (\$maskList[0] == \"subdivMeshPoint\");
    \$isLocatorObject = (\$maskList[0] == \"locator\");
    \$isMotionTrail = (\$maskList[0] == \"motionTrail\");
}
if (2 <= size(\$maskList)) {
    \$isBezierObject = (\$maskList[1] == \"bezierAnchor\");
    \$isNurbObject = (\$maskList[1] == \"controlVertex\");
    \$isPolyObject = (\$maskList[1] == \"vertex\");
}")?>
<p>Et n'&eacute;diter que la partie que vous souhaitez voir modifier dans la proc&eacute;dure MEL. Ce qui peut vite s'av&eacute;rer p&eacute;nible...</p>
<p>Fort heureusement pour vous, nous sommes l&agrave;, avec une solution toute pr&ecirc;te pour rem&eacute;dier &agrave; la p&eacute;nibilit&eacute; de devoir &eacute;diter soi-m&ecirc;me cette longue proc&eacute;dure,
en t&eacute;l&eacute;chargeant ce simple script <b>radialDesigner</b></p>
<?php addImage("hotbox_designer.jpg")?>
<?php 
	$_GET['n'] = 'radial_designer';
	$_GET['buttons'] = true;
	include_once './dl/wrap/index.php';
?>
<p>L'usage est on ne peut plus simple, il vous suffit de s&eacute;lectionner dans la liste situ&eacute;e tout en haut de la fen&ecirc;tre sur quel type d'objet vous souhaitez 
appliquer le changement de menu, puis de cliquer sur les menuItems que vous souhaitez &eacute;diter, en changeant leur noms et fonction, mais les images parlent
quelque fois plus que les mots eux-m&ecirc;mes, je vous laisse donc avec cette vid&eacute;o de d&eacute;monstration =) ;</p>

<?php addVideo("189525134")?>

<dt id="45"></dt><h2>Connexion &agrave; un scriptNode</h2>
<p>La seconde 'astuce' de l'histoire est de faire rentrer tout ce beau monde (ie la proc&eacute;dure <b>createSelectMenuItems</b>) dans un scriptNode qui sera charg&eacute; &agrave;
chaque fois que la sc&egrave;ne sera lanc&eacute;e, ce qui peut &ecirc;tre utile dans un studio par exemple, lorsqu'un riggeur d&eacute;fini ce menu et l'int&egrave;gre &agrave; sa sc&egrave;ne, pour que 
chaque animateur l'ait &agrave; son tour et puisse animer plus rapidement, plus efficacement =).</p>
<p>La m&eacute;thode est assez simple, compte tenu de ce que nous avons vu au dessus, il suffit de cr&eacute;er une variable multi-ligne en MEL dans laquelle on copie la
proc&eacute;dure, puis on appelle la fonction <?php cmds("evalDeferred")?> qui va ex&eacute;cuter la fonction lorsque Maya sera correctement charg&eacute;, et donc <u>apr&egrave;s</u>
que l'UI soit charg&eacute;e, avec son markingMenu par d&eacute;faut.</p>
<p>Je vous invite pour ce faire &agrave; utiliser le script pr&eacute;c&eacute;demment &eacute;voqu&eacute;, cela vous fera gagner du temps, et un &eacute;conomie de cheveux arrach&eacute;s pour imbriquer 
une string dans une string dans une autre string via les antislashs =) !</p>