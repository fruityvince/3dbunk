<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Synopsis</h1>
<p>Nous allons aborder dans ce tutorial tr&egrave;s simple un
	syst&egrave;me pour passer une curve de notre rig de dure &agrave;
	arrondie, ce qui peut tr&egrave;s bien &ecirc;tre adapt&eacute; aussi
	sur une nurbsSurface. L'usage &agrave; en faire est divers mais
	s'applique plus au rig, cela peut permettre de contr&ocirc;ler si l'on
	veut des angles durs ou arrondis. Par exemple si l'on veut avoir un
	switch entre des doigts humains ou des doigts arrondis. Ou une colonne
	vert&eacute;brale rigide ou courb&eacute;e =).</p>

<dt id="20"></dt><h1>Le concept</h1>
<dt id="21"></dt><h2>La m&eacute;thode</h2>
<p>La m&eacute;thode est la suivante ;</p>
<p>Nous partons d'une curve skinn&eacute;e &agrave; quelques joints,
	nous la rebuildons afin d'en avoir une copie aux angles arrondis, cette
	copie sera rebuild&eacute; une seconde fois avec un grand nombre de
	knot (ou point) pour conserver la courbure, et nous rebuildons
	notre curve originale avec le m&ecirc;me (grand) nombre de knot, un
	blendShape entre les deux et l'affaire est r&eacute;gl&eacute;e !
	Allons-y, Alonzo.</p>

<dt id="22"></dt><h2>Je duplique, tu dupliques, il duplique...</h2>
<p>
	Commen&ccedil;ons par cr&eacute;er une simple curve &agrave; <b>1</b>
	degr&eacute;.
</p>
<?php addImage("01.gif")?>
<p>Avec deux - trois joints, saupoudrons le tout d'un skinCluster, nous
	obtenons un superbe rig de curve ;</p>
<?php addImage("02.gif")?>
<p>Voici une petite version 'script' de l'exemple d&eacute;crit
	ci-dessus ;</p>
<?php

createCodeX ( "from maya import cmds

positions = [(i, 0, 0) for i in range(4)]

crv = cmds.curve(p=positions, d=1, n='crv_origin')

cmds.select(cl=True)
jts = [cmds.joint(p=pos, n='jt_skin_%02d' % pos[0]) for pos in positions]

cmds.skinCluster(crv, jts, tsb=True, mi=1)", "Creation et skin de d'une curve", true )?>
<p>
	Ouvrons les options de l'outil <b>Rebuild Curve</b> de Maya afin d'en
	cr&eacute;er une nouvelle version arrondie, pour ce faire vous pouvez
	faire <b>Barre d'espace &rarr; Edit Curves &rarr; Rebuild Curve &rarr;
		...</b> et ouvrez les options. Activez le mode <i>Uniform</i> et
	r&eacute;glez le nombre de degr&eacute;s &agrave; <i>3</i> afin d'avoir
	une copie de votre curve originale, mais arrondie. Le <i>Number of
		spans</i> devrait correspondre &agrave; votre nombre de points moins
	1.
</p>
<p>
	Pensez bien &agrave; activer l'option <i>Keep original</i> !
</p>

<?php addImage("03.jpg", "Rebuild Curve Options")?>

<?php addTip ( "L'option <b>Keep original</b> pr&eacute;sente dans de nombreux outils Maya permet de cr&eacute;er 
une copie de l'objet de r&eacute;f&eacute;rence avec ses d&eacute;formeurs et attributs, pratiques lorsque l'ont 
veut avoir une copie 'dynamique' d'un objet." )?>

<dt id="23"></dt><h2>Conformons</h2>
<p>
	Nous avons nos deux curves, nommons-les '<b>crv_origin</b>' et '<b>crv_smooth</b>'.
	L'une arrondie et l'autre originale avec des angles durs. Le hic, si
	nous voulons cr&eacute;er un blendShape entre ces deux curves, l'une
	ayant plus de points que l'autre, sans surprise le r&eacute;sultat ne
	sera pas concluant et notre curve originale se conformera aux premiers
	points de la courbe smooth&eacute;e, pour pallier &agrave; ce
	probl&egrave;me nous allons rebuilder nos deux curves, avec un nombre
	&eacute;lev&eacute; de points afin de les conformer et de pouvoir faire
	un blendShape entre les deux.
</p>
<p>
	Ex&eacute;cutons donc deux autres <b>Rebuild Curve</b> avec des
	r&eacute;glages identiques, l'un sur la courbe originale et l'autre sur
	notre courbe arrondie.
</p>
<ul>
	<li>Avec un grand nombre de spans (id&eacute;alement faites en sorte
		que ce nombre de points soit un multiple du nombre de spans original,
		pour notre exemple nous en avons mis 10 fois plus, donc 30 en tout.</li>
	<li>D&eacute;sactivez l'option <i>Keep original</i>
	</li>
	<li>Et r&eacute;duisez le nombre de degr&eacute;s &agrave; 1 afin de ne
		pas trop alourdir notre sc&egrave;ne (oui bon... Elle est vide notre
		sc&egrave;ne mais imaginez rigger les dents d'une baleine avec ce
		syst&egrave;me... Pensons l&eacute;ger :] )</li>
</ul>
<?php addImage("04.jpg", "Options pour un rebuild Curve du tonnerre")?>
<p>Nous pouvons maintenant cr&eacute;er un blendShape propre entre nos
	deux curves...</p>
<dt id="30"></dt><h1>Finalisation</h1>
<p>
	Tr&egrave;s bien ! S&eacute;lectionnons donc notre curve arrondie '<b>crv_smooth</b>'
	puis notre curve dure '<b>crv_origin</b>' et cr&eacute;ons un <i>blendShape</i>
	en faisant <b>Barre d'espace &rarr; Create Deformers &rarr; Blend Shape</b>.
	Super, nous avons maintenant notre blendshape pour transformer notre
	curve &agrave; angles durs en curve arrondie !
</p>
<p>Essayons d'activer le blendshape...</p>
<p>Aie !!!</p>
<dt id="31"></dt><h2>Le grain de sable</h2>
<?php addImage("05.jpg")?>
<p>
	L'ire de Maya est sur nous ! La pire des erreurs s'abat sur nos pauvres
	&ecirc;tres, le <b>Cycle</b> !
</p>
<p>
	Mais comment faire ?... Pour le cas pr&eacute;sent &ccedil;a ne va pas
	&ecirc;tre tr&egrave;s compliqu&eacute;, Maya essaie simplement de
	faire un blendShape entre deux objets mais l'un de ces deux objets fait
	r&eacute;f&eacute;rence au premier, une r&eacute;f&eacute;rence en
	boucle apparait d'o&ugrave; l'erreur. Pour la corriger il va nous
	falloir reconnecter les nodes en changeant simplement la source du node
	de <?php node("rebuildCurve")?> de notre curve arrondie.
</p>
<dt id="32"></dt><h2>Coup de balai</h2>
<p>
	En utilisant le <b>Node Editor</b>, s&eacute;lectionnez votre curve
	originale, susnomm&eacute;e '<b>crv_origin</b>' et graphez la shape,
	vous devriez voir quelque chose d'approchant ;
</p>
<?php addImage("06.jpg")?>
<p>
	On voit donc que le output de notre curve originale retourne dans le
	<?php node("rebuildCurve")?> qui lui m&ecirc;me est envoy&eacute; dans le blendShape,
	conduisant &agrave; notre boucle infinie, or nous n'avons besoin des
	informations de la curve originale qu'&agrave; partir du skinCluster,
	nous allons donc reconnecter l'<b>outputGeometry</b> de notre
	skinCluster &agrave; l'<b>inputCurve</b> du rebuildCurve, comme
	&ccedil;a ;
</p>
<?php addImage("07.jpg")?>
<p>Ou vous pouvez tout simplement taper les lignes de code suivantes ;</p>
<?php

createCodeX ( "skincluster = 'skinCluster1'        # le nom de votre skinCluster original
rebuild = 'rebuildCurve3'           # le nom de votre rebuildCurve smooth
cmds.connectAttr('%s.og[0]' % skincluster, '%s.ic' % rebuild, f=True)" )?>
<p>
	Et voil&agrave; ! Cachez maintenant la '<b>crv_smooth</b>' et vous
	pouvez jouer avec le poids de votre blendShape pour passer d'une curve
	arrondie &agrave; une curve dure tout en conservant les
	pr&eacute;c&eacute;dent d&eacute;formeurs affectant '<b>crv_origin</b>'
	! Comme &ccedil;a ;
</p>
<?php addImage("08.gif")?>
<dt id="32"></dt><h2>Code & Extensibilit&eacute;</h2>
<p>Bon... pour les plus fain&eacute;ants, voici le code entier qui
	retraduit le tuto =) ! C'est un exemple tr&egrave;s simple mais qui
	permet de voir l'importance de votre pile de d&eacute;formeurs, l'ordre
	et l'interoperabilit&eacute; entre ses &eacute;l&eacute;ments et les
	diff&eacute;rents objets de votre sc&egrave;ne.</p>
<?php

createCodeX ( "from maya import cmds

length = 4      # le nombre de points sur notre curve

# parametres par defaut de nos rebuild
default_params = {'rt': 0, 'end': 1, 'kr': 0, 'kcp': 0,
		'kep': 1, 'kt': 0, 'tol': 0.01, 'ch': 1}

# notre champ de positions
positions = [(i, 0, 0) for i in range(length + 1)]

crv = cmds.curve(p=positions, d=1, n='crv_origin')

cmds.select(cl=True)
jts = [cmds.joint(p=pos, n='jt_skin_%02d' % pos[0]) for pos in positions]

skincluster = cmds.skinCluster(crv, jts, tsb=True, mi=1)[0]
crv_smooth, rebuild = cmds.rebuildCurve(crv, s=length - 1, d=3, rpo=0, **default_params)

# on conforme les deux curves
cmds.rebuildCurve(crv, s=length * 10, d=1, rpo=1, **default_params)
cmds.rebuildCurve(crv_smooth, s=length * 10, d=1, rpo=1, **default_params)

# finalisation
cmds.blendShape(crv_smooth, crv)
cmds.connectAttr('%s.og[0]' % skincluster, '%s.ic' % rebuild, f=True)

# on cache la curve smooth
cmds.setAttr('%s.v' % crv_smooth, 0)

# pour notre exemple
cylinder = cmds.polyCylinder(r=0.2, h=length, sx=10, sy=length * 10, sz=1, ax=(1, 0, 0))
cmds.move(length / 2, 0, 0, cylinder, r=True)
wire = cmds.wire(cylinder, w=crv)
cmds.setAttr('%s.dropoffDistance[0]' % wire[0], 10)", "Curve hard 'n round", true )?>
<br>
<?php
addNote ( "Pour cet exemple nous cr&eacute;ons un simple cylindre avec un wire pour mettre en lumi&egrave;re notre 
travail de fa&ccedil;on plus explicite, bougez les joints puis s&eacute;lectionnez la curve pour faire varier la 
duret&eacute; des angles !")?>
<h2>Extension</h2>
<p>
	Notez que ce tuto peut &ecirc;tre &eacute;tendu aux nurbsSurfaces, le
	principe &eacute;tant exactement le m&ecirc;me avec l'outil <?php node("rebuildSurface")?>