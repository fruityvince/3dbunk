from PySide.QtGui import *
from PySide.QtCore import *
from shiboken import wrapInstance
from maya.OpenMayaUI import MQtUtil

class Rule():
    def __init__(self, fg_color, pattern='', bg_color=None, bold=False, italic=False):
        self.pattern = QRegExp(pattern)
        self.form = QTextCharFormat()
        self.form.setForeground(QColor(*fg_color))
        if bg_color:
            self.form.setBackground(QColor(*bg_color))
        font = QFont('Courier New', 9)
        font.setBold(bold)
        font.setItalic(italic)
        self.form.setFont(font)

class StdOut_Syntax(QSyntaxHighlighter):
    def __init__(self, parent, rules):
        super(StdOut_Syntax, self).__init__(parent)
        self.parent = parent
        self.rules = rules

    def highlightBlock(self, text):
        # on iter a travers nos regles
        for rule in self.rules:
            pattern = rule.pattern
            index = pattern.indexIn(text)
            while index >= 0:
                len = pattern.matchedLength()
                self.setFormat(index, len, rule.form)
                index = pattern.indexIn(text, index + len)
            self.setCurrentBlockState(0)
        
def wrap():
    i = 1
    while i:
        try:
            se_edit = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter%i' % i)),
                                                            QTextEdit)
            # we remove the old syntax and raise an exception to get out of the while
            assert se_edit.findChild(QSyntaxHighlighter).deleteLater()
        except TypeError:
            i += 1       # if we don't find the widget we increment
        except (AttributeError, AssertionError):
            break

    rules = [Rule((212, 160, 125), r'^//.+$', bold=True),
             Rule((185, 125, 255), r'^#.+$', italic=True),
             Rule((255, 175, 44), r'^#.*(error|Error).+$')]

    StdOut = StdOut_Syntax(se_edit, rules)
    return StdOut
StdOut = wrap()