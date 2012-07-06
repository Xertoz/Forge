var forge = typeof(forge) != 'object' ? {} : forge;

forge.statistics = {
    data: null,
    diagram: null,
    mouse: null
};

forge.statistics.data = function(set) {
    var max = set[0];
    var min = set[0];
    
    for (var i=0;i<set.length;i++)
        if (max < set[i])
            max = set[i];
        else if (min > set[i])
            min = set[i];
    
    return {
        extreme: {
            max: max,
            min: min
        },
        entries: set
    };
};

forge.statistics.diagram = function(width,height) {
    width = Number(width);
    height = Number(height);
    
    var canvas = document.createElement('canvas');
    
    canvas.innerHTML = 'Your browser does not support HTML5 canvases and you will not be able to see this diagram.';
    canvas.width = width;
    canvas.height = height;
    
    var obj = {
        axis: {
            bottom: {
                label: null,
                show: false
            },
            left: {
                label: null,
                show: false
            },
            top: {
                label: null,
                show: false
            },
            right: {
                label: null,
                show: false
            }
        },
        
        diagram: {
            extreme: {
                max: 0,
                min: 0
            },
            height: 0,
            offset: {
                left: 0,
                top: 0
            },
            width: 0
        },
        
        AXIS_BOTTOM: 1,
        AXIS_LEFT: 2,
        AXIS_TOP: 4,
        AXIS_RIGHT: 8,
        canvas: canvas,
        height: height,
        sets: new Array(),
        width: width,
        worker: canvas.getContext('2d'),
        
        add: function(set) {
            this.sets.push(set);
        },
        
        draw: function() {
            this.diagram.width = this.width-(this.axis.left.show+this.axis.right.show)*25;
            this.diagram.height = this.height-(this.axis.top.show+this.axis.bottom.show)*15;
            this.diagram.offset.left = this.axis.left.show*25;
            this.diagram.offset.top = this.axis.top.show*15+this.diagram.height;
            
            if (this.axis.left.show) {
                this.worker.rotate(-Math.PI/2);
                this.worker.fillText(this.axis.left.label,-this.height/2,15);
                this.worker.rotate(Math.PI/2);
                this.worker.fillRect(25,0,1,this.height-15);
            }
            
            if (this.axis.bottom.show) {
                this.worker.fillText(this.axis.bottom.label,this.width/2,this.height);
                this.worker.fillRect(25,this.height-15,this.width,1);
            }
            
            // Find the maximum and minimum points of this diagram
            this.diagram.extreme.max = this.sets[0].extreme.max;
            this.diagram.extreme.min = this.sets[0].extreme.min;
            for (var i=0;i<this.sets.length;i++)
                if (this.diagram.extreme.max < this.sets[i].extreme.max)
                    this.diagram.extreme.max = this.sets[i].extreme.max;
                else if (this.diagram.extreme.min > this.sets[i].extreme.min)
                    this.diagram.extreme.min = this.sets[i].extreme.min;
            
            for (var s=0;s<this.sets.length;s++) {
                var set = this.sets[s];
                
                this.worker.beginPath();
                
                for (var i=0;i<set.entries.length;i++) {
                    if (i==0)
                        this.worker.moveTo(this.diagram.offset.left+this.diagram.width*i/set.entries.length,this.diagram.offset.top-this.diagram.height*set.entries[i]/this.diagram.extreme.max);
                    else
                        this.worker.lineTo(this.diagram.offset.left+this.diagram.width*i/set.entries.length,this.diagram.offset.top-this.diagram.height*set.entries[i]/this.diagram.extreme.max);
                }
                
                this.worker.stroke();
                this.worker.closePath();
            }
        },
        
        setAxis: function(where,label) {
            switch (where) {
                case this.AXIS_BOTTOM:
                    this.axis.bottom.show = true;
                    this.axis.bottom.label = label;
                break;
                case this.AXIS_LEFT:
                    this.axis.left.show = true;
                    this.axis.left.label = label;
                break;
                case this.AXIS_TOP:
                    this.axis.top.show = true;
                    this.axis.top.label = label;
                break;
                case this.AXIS_RIGHT:
                    this.axis.right.show = true;
                    this.axis.right.label = label;
                break;
            }
        }
    };
    
    canvas.addEventListener('mousemove',function(evt){forge.statistics.mouse(obj,evt);},false);
    
    return obj;
};

forge.statistics.mouse = function(diagram,evt) {
    var x = evt.clientX-diagram.canvas.offsetLeft;
    var y = evt.clientY-diagram.canvas.offsetTop;
}

console.info('Forge Statistics API loaded');