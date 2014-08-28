var DrawBox = new Class({
	Implements: Options,
	options: {
		drawableArea: document,
		insertArea: null
	},
	
	mouseDown: false,
	box: null,
	begin: {},
	drawableArea: null,
	drawableAreaStyles: {width: 0, height: 0},
	insertArea: null,
	
	initialize: function(options)
	{
		this.setOptions(options);
		this.drawableArea = this.options.drawableArea;
		this.insertArea = this.options.insertArea;
		this.offsetInsertArea = this.options.insertArea.getPosition();
		
		this.mousemoveBound = this.mousemove.bind(this);
		this.mouseupBound = this.mouseup.bind(this);
		this.mousemoveMoveBound = this.mousemoveMove.bind(this);
		this.mouseupMoveBound = this.mouseupMove.bind(this);
		this.drawableArea.addEvent('mousedown', this.mousedown.bindWithEvent(this));
		
		this.drawableAreaStyles.height = this.drawableArea.getStyle('height').toInt();
		this.drawableAreaStyles.width = this.drawableArea.getStyle('width').toInt();
	},
	
	bindDrawEvents: function()
	{
		this.drawableArea.addEvent('mousemove', this.mousemoveBound);
		this.drawableArea.addEvent('mouseup', this.mouseupBound);
	},		
	
	unbindDrawEvents: function()
	{
		this.drawableArea.removeEvent('mousemove', this.mousemoveBound);
		this.drawableArea.removeEvent('mouseup', this.mouseupBound);
	},		
	
	bindMoveEvents: function()
	{
		this.drawableArea.addEvent('mousemove', this.mousemoveMoveBound);
		this.drawableArea.addEvent('mouseup', this.mouseupMoveBound);
	},		
	
	unbindMoveEvents: function()
	{
		this.drawableArea.removeEvent('mousemove', this.mousemoveMoveBound);
		this.drawableArea.removeEvent('mouseup', this.mouseupMoveBound);
	},		
	
	getMousePositionInInsertArea: function(e)
	{
		return {x: e.page.x - this.offsetInsertArea.x, y: e.page.y - this.offsetInsertArea.y};
	},
	
	mousedown: function(e)
	{
		if(e.rightClick) return;
		if(e.target.hasClass('boxX'))
		{
			e.target.getParent('.box').destroy();
			this.savePosition();
			return;
		}
		
		this.mouseDown = true;
		$(document.body).addClass('selectionMode');
		
		if(e.target.hasClass('box'))
		{
			this.offset = {x: e.event.offsetX, y: e.event.offsetY};
			this.box = e.target;
 			this.bindMoveEvents();
		}
		else
		{
 			this.bindDrawEvents();
 			this.begin = this.getMousePositionInInsertArea(e);
			this.box = new Element("div", {'class': 'box'}).inject(this.insertArea, 'top');
			this.box.setStyles({left: this.begin.x, top: this.begin.y});
		}
	},
				
	mousemove: function(e)
	{
		if(!this.mouseDown || e.event.which == 0) {
			this.mouseup(e);
			return;
		}

		var now = this.getMousePositionInInsertArea(e);
		var width = now.x - this.begin.x;
		var height = now.y - this.begin.y;
		var styles = {width : width, height: height};
		if(width < 0) {
			styles.left = this.begin.x + width;
			styles.width = Math.abs(width);
		}
		if(height < 0) {
			styles.top = this.begin.y + height;
			styles.height = Math.abs(height);
		}
		this.box.setStyles(styles);
	},
	
	mouseup: function(e)
	{
		this.unbindDrawEvents();

		var coords = this.box.getCoordinates(this.insertArea);
		if(coords.width < 5 || coords.height < 5)
		{
			this.box.destroy();
			return;
		}
		
		this.mouseDown = false;
		this.box.setStyle('border', '2px solid red');
		new Element("div", {'class': 'boxX'}).set('text', 'X').inject(this.box);
		$(document.body).removeClass('selectionMode');
		this.savePosition();
	},
	
	mousemoveMove: function(e)
	{
		if(!this.mouseDown) {
			this.unbindMoveEvents();
			return;
		}
		var now = this.getMousePositionInInsertArea(e);
		
		var newX = now.x - this.offset.x;
		var newY = now.y - this.offset.y;
		
		var styles = {};
		
		if(newX + this.box.getStyle('width').toInt() < this.drawableAreaStyles.width)
		{
			styles.left = Math.max(0, newX);
		}
		
		if(newY + this.box.getStyle('height').toInt() < this.drawableAreaStyles.height)
		{
			styles.top = Math.max(0, newY);
		}
		
		this.box.setStyles(styles);
	},
	
	mouseupMove: function(e)
	{
		this.unbindMoveEvents();
		$(document.body).removeClass('selectionMode');
		this.savePosition();
	},

	savePosition: function()
	{
		var drawings = {};
		var boxCount = 0;
		this.insertArea.getElements('.box').each(function(el) {
			if(typeof drawings.box == "undefined") drawings.box = [];
			drawings.box.push( el.getCoordinates(this.insertArea) );
			boxCount++;
		}.bind(this));
		
		new Request.JSON({
			url: location.href.replace(/\/[^\/]*$/, '') + '/savedrawings.php'
		}).post({token: token, drawings: drawings, boxCount: boxCount });
		
	}

});

function drawInitialDrawings()
{
	if(typeof(drawings.box) != "undefined")
	{
		drawings.box.each(function(boxstyle) {
			var box = new Element("div", {'class': 'box'}).inject($('pictureArea'), 'top');
			new Element("div", {'class': 'boxX'}).set('text', 'X').inject(box);
			boxstyle['border'] = '2px solid red';
			box.setStyles(boxstyle);
		});
	}	
}

window.addEvent('load', function() {
	
	if(typeof(drawings) != "undefined")
	{
		drawInitialDrawings();
	}
	
	var drawBox = new DrawBox({ drawableArea:  $('pictureArea'), insertArea: $('pictureArea') });
});