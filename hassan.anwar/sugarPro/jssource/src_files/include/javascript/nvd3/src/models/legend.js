nv.models.legend = function() {

  //============================================================
  // Public Variables with Default Settings
  //------------------------------------------------------------

  var margin = {top: 0, right: 0, bottom: 0, left: 0},
      width = 0,
      height = 0,
      align = 'right',
      direction = 'ltr',
      position = 'start',
      radius = 5,
      gutter = 10,
      equalColumns = true,
      showAll = false,
      collapsed = false,
      rowsCount = 3, //number of rows to display if showAll = false
      enabled = false,
      strings = {close: 'Hide legend', type: 'Show legend'},
      id = Math.floor(Math.random() * 10000), //Create semi-unique ID in case user doesn't select one
      getKey = function(d) {
        return d.key.length > 0 || (!isNaN(parseFloat(d.key)) && isFinite(d.key)) ? d.key : 'undefined';
      },
      color = function(d, i) { return nv.utils.defaultColor()(d, i); },
      classes = function(d, i) { return ''; },
      dispatch = d3.dispatch('legendClick', 'legendMouseover', 'legendMouseout', 'toggleMenu', 'closeMenu');

  // Private Variables
  //------------------------------------------------------------

  var legendOpen = 0;

  //============================================================

  function legend(selection) {

    selection.each(function(data) {

      var container = d3.select(this),
          containerWidth = width,
          containerHeight = height,
          keyWidths = [],
          legendHeight = 0,
          dropdownHeight = 0,
          type = '';

      if (!data || !data.length || !data.filter(function(d) { return !d.values || d.values.length; }).length) {
        return legend;
      }

      enabled = true;

      type = !data[0].type || data[0].type === 'bar' ? 'bar' : 'line';

      if (direction === 'rtl') {
        if (align === 'left') {
          align = 'right';
        } else if (align === 'right') {
          align = 'left';
        }
      }

      //------------------------------------------------------------
      // Setup containers and skeleton of legend

      var wrap = container.selectAll('g.nv-chart-legend').data([data]);
      var wrapEnter = wrap.enter().append('g').attr('class', 'nv-chart-legend');

      wrapEnter.append('defs')
        .append('clipPath').attr('id', 'nv-edge-clip-' + id)
        .append('rect');
      var defs = wrap.select('defs');
      var clip = wrap.select('#nv-edge-clip-' + id + ' rect');

      wrapEnter
        .append('rect').attr('class', 'nv-legend-background');
      var back = container.select('.nv-legend-background');

      wrapEnter
        .append('text').attr('class', 'nv-legend-link');
      var link = container.select('.nv-legend-link');

      wrapEnter
        .append('g').attr('class', 'nv-legend-mask')
        .append('g').attr('class', 'nv-legend');
      var mask = container.select('.nv-legend-mask');
      var g = container.select('g.nv-legend');
      g .attr('transform', 'translate(0,0)');

      var series = g.selectAll('.nv-series')
            .data(function(d) { return d; }, function(d) { return d.key; });
      var seriesEnter = series.enter().append('g').attr('class', 'nv-series');
      series.exit().remove();

      var zoom = d3.behavior.zoom();

      function zoomLegend(d) {
        var trans = d3.transform(g.attr('transform')).translate,
          transX = trans[0],
          transY = trans[1] + d3.event.sourceEvent.wheelDelta / 4,
          diffY = dropdownHeight - legendHeight,
          upMax = Math.max(transY, diffY); //should not go beyond diff
        if (upMax) {
          g .attr('transform', 'translate(' + transX + ',' + Math.min(upMax, 0) + ')');
        }
      }

      clip
        .attr('x', 0.5)
        .attr('y', 0.5)
        .attr('width', 0)
        .attr('height', 0);

      back
        .attr('x', 0.5)
        .attr('y', 0.5)
        .attr('width', 0)
        .attr('height', 0)
        .style('opacity', 0)
        .style('pointer-events', 'all');

      link
        .text(legendOpen === 1 ? legend.strings().close : legend.strings().open)
        .attr('text-anchor', align === 'left' ? direction === 'rtl' ? 'end' : 'start' : direction === 'rtl' ? 'start' : 'end')
        .attr('dy', '.32em')
        .attr('dx', 0)
        .style('opacity', 0)
        .on('click', function(d, i) {
          dispatch.toggleMenu(d, i);
        });

      seriesEnter
        .on('mouseover', function(d, i) {
          dispatch.legendMouseover(d, i);  //TODO: Make consistent with other event objects
        })
        .on('mouseout', function(d, i) {
          dispatch.legendMouseout(d, i);
        })
        .on('click', function(d, i) {
          dispatch.legendClick(d, i);
          d3.event.stopPropagation();
        });

      if (type === 'bar') {

        seriesEnter.append('circle')
          .attr('r', radius)
          .style('stroke-width', 2);

        series.selectAll('circle')
          .attr('class', function(d, i) {
            return classes(d, d.hasOwnProperty('series') ? d.series : i);
          })
          .attr('fill', function(d, i) {
            return color(d, d.hasOwnProperty('series') ? d.series : i);
          })
          .attr('stroke', function(d, i) {
            return color(d, d.hasOwnProperty('series') ? d.series : i);
          });

        seriesEnter.append('text')
          .attr('dy', '.36em');
        series.select('text')
          .text(getKey);

      } else {

        seriesEnter.append('circle')
          .style('stroke-width', 0);
        seriesEnter.append('line')
          .attr('x0', 0)
          .attr('y0', 0)
          .attr('y1', 0)
          .style('stroke-width', '4px');
        seriesEnter.append('circle')
          .style('stroke-width', 0);

        series.select('line')
          .attr('class', function(d, i) {
            return classes(d, d.hasOwnProperty('series') ? d.series : i);
          })
          .attr('stroke', function(d, i) {
            return color(d, d.hasOwnProperty('series') ? d.series : i);
          });

        series.selectAll('circle')
          .attr('r', function(d, i) {
            return d.type === 'dash' ? 0 : radius;
          })
          .attr('class', function(d, i) {
            return classes(d, d.hasOwnProperty('series') ? d.series : i);
          })
          .attr('fill', function(d, i) {
            return color(d, d.hasOwnProperty('series') ? d.series : i);
          });

        seriesEnter.append('text')
          .attr('dy', '.32em')
          .attr('dx', 0);
        series.select('text')
          .text(getKey)
          .attr('text-anchor', position);

      }

      series.classed('disabled', function(d) {
        return d.disabled;
      });

      //------------------------------------------------------------

      //TODO: add ability to add key to legend
      //var label = g.append('text').text('Probability:').attr('class','nv-series-label').attr('transform','translate(0,0)');

      // store legend label widths
      legend.calculateWidth = function() {

        var padding = gutter + (position === 'start' ? 2 * radius + 3 : 0);
        keyWidths = [];

        g.style('display', 'inline');

        series.select('text').each(function(d, i) {
          var textWidth = d3.select(this).node().getBBox().width;
          keyWidths.push(Math.max(Math.floor(textWidth) + padding, 50));
        });

        legend.width(d3.sum(keyWidths) - gutter);

        return legend.width();
      };

      legend.getLineHeight = function() {
        g.style('display', 'inline');
        var lineHeightBB = Math.floor(series.select('text').node().getBBox().height);
        return lineHeightBB;
      };

      legend.arrange = function(containerWidth) {

        if (keyWidths.length === 0) {
          this.calculateWidth();
        }

        var keys = keyWidths.length,
            rows = 1,
            cols = keys,
            columnWidths = [],
            keyPositions = [],
            maxWidth = containerWidth - margin.left - margin.right,
            maxRowWidth = 0,
            minRowWidth = 0,
            lineSpacing = position === 'start' ? 10 : 6,
            textHeight = this.getLineHeight(),
            lineHeight = lineSpacing + radius * 2 + (position === 'start' ? 0 : textHeight),
            xpos = 0,
            ypos = 0,
            i,
            mod,
            shift,
            padding = radius + radius * 2;

        if (equalColumns) {

          //keep decreasing the number of keys per row until
          //legend width is less than the available width
          while (cols > 0) {
            columnWidths = [];

            for (i = 0; i < keys; i += 1) {
              if (keyWidths[i] > (columnWidths[i % cols] || 0)) {
                columnWidths[i % cols] = keyWidths[i];
              }
            }

            if (d3.sum(columnWidths) - gutter < maxWidth) {
              break;
            }
            cols -= 1;
          }
          cols = cols || 1;

          rows = Math.ceil(keys / cols);
          maxRowWidth = d3.sum(columnWidths) - gutter;

          for (i = 0; i < keys; i += 1) {
            mod = i % cols;

            if (position === 'start') {
              if (mod === 0) {
                xpos = direction === 'rtl' ? maxRowWidth : 0;
              } else {
                xpos += columnWidths[mod - 1] * (direction === 'rtl' ? -1 : 1);
              }
            } else {
              if (mod === 0) {
                xpos = (direction === 'rtl' ? maxRowWidth : 0) + (columnWidths[mod] - gutter) / 2 * (direction === 'rtl' ? -1 : 1);
              } else {
                xpos += (columnWidths[mod - 1] + columnWidths[mod]) / 2 * (direction === 'rtl' ? -1 : 1);
              }
            }

            ypos = Math.floor(i / cols) * lineHeight;
            keyPositions[i] = {x: xpos, y: ypos};
          }

        } else {

          if (direction === 'rtl') {

            xpos = maxWidth;

            for (i = 0; i < keys; i += 1) {
              if (xpos - keyWidths[i] + gutter < 0) {
                maxRowWidth = Math.max(maxRowWidth, keyWidths[i] - gutter);
                xpos = maxWidth;
                if (i) {
                  rows += 1;
                }
              }
              if (xpos - keyWidths[i] + gutter > maxRowWidth) {
                maxRowWidth = xpos - keyWidths[i] + gutter;
              }
              keyPositions[i] = {x: xpos, y: (rows - 1) * (lineSpacing + radius * 2)};
              xpos -= keyWidths[i];
            }

          } else {

            xpos = 0;

            for (i = 0; i < keys; i += 1) {
              if (i && xpos + keyWidths[i] - gutter > maxWidth) {
                xpos = 0;
                rows += 1;
              }
              if (xpos + keyWidths[i] - gutter > maxRowWidth) {
                maxRowWidth = xpos + keyWidths[i] - gutter;
              }
              keyPositions[i] = {x: xpos, y: (rows - 1) * (lineSpacing + radius * 2)};
              xpos += keyWidths[i];
            }

          }

        }

        if (showAll || rows < rowsCount + 1) {

          legendOpen = 0;
          collapsed = false;

          legend
            .width(margin.left + maxRowWidth + margin.right)
            .height(margin.top + rows * lineHeight - lineSpacing + margin.bottom + 1);

          switch (align) {
            case 'left':
              shift = 0; //legend.width() - containerWidth;
              break;
            case 'center':
              shift = (containerWidth - legend.width()) / 2;
              break;
            case 'right':
              shift = 0; //containerWidth - legend.width();// * (direction === 'rtl' ? -1 : 1);
              break;
          }

          zoom
            .on('zoom', null);

          clip
            .attr('y', 0)
            .attr('width', legend.width())
            .attr('height', legend.height());

          back
            .attr('x', shift)
            .attr('width', legend.width())
            .attr('height', legend.height())
            .attr('rx', 0)
            .attr('ry', 0)
            .attr('filter', 'none')
            .style('display', 'inline')
            .style('opacity', 0);

          mask
            .attr('clip-path', 'none')
            .attr('transform', function(d, i) {
              var xpos = shift + margin.left + (position === 'start' ? (direction === 'rtl' ? -5 : 5) : 0);
              return 'translate(' + xpos + ',' + (1 + margin.top + radius) + ')';
            });

          g
            .style('opacity', 1)
            .style('display', 'inline');

          series
            .attr('transform', function(d, i) {
              var pos = keyPositions[i];
              return 'translate(' + pos.x + ',' + pos.y + ')';
            });

          series
            .selectAll('text')
              .attr('text-anchor', position)
              .attr('transform', function(d, i) {
                var xpos = position === 'start' ? direction === 'rtl' ? -8 : 8 : 0,
                    ypos = position === 'start' ? 0 : textHeight;
                return 'translate(' + xpos + ',' + ypos + ')';
              });
          series
            .selectAll('circle')
              .attr('transform', function(d, i) {
                var xpos = position === 'start' || type === 'bar' ? 0 : (i ? 15 : -15);
                return 'translate(' + xpos + ',0)';
              });
          series
            .selectAll('line')
              .attr('x1', function(d, i) {
                return d.type === 'dash' ? 40 : 30;
              })
              .attr('transform', function(d, i) {
                return d.type === 'dash' ? 'translate(-20,0)' : 'translate(-15,0)';
              })
              .style('stroke-dasharray', function(d, i) {
                return d.type === 'dash' ? '8, 8' : '0,0';
              });

        } else {

          collapsed = true;

          legend
            .width(radius * 2 + d3.max(keyWidths) - gutter + (position === 'start' ? 0 : radius * 2 + 3) + radius * 2)
            .height(radius * 2 + radius * 2 + radius * 2);

          legendHeight = radius * 2 + radius * 2 * keys + (keys - 1) * 10 + radius * 2;//TODO: why is this 10 hardcoded?
          dropdownHeight = Math.min(containerHeight - legend.height(), legendHeight);

          zoom
            .on('zoom', zoomLegend);

          clip
            .attr('x', 0.5 - padding)
            .attr('y', 0.5 - padding)
            .attr('width', legend.width())
            .attr('height', dropdownHeight);

          back
            .attr('x', 0.5)
            .attr('y', 0.5 + legend.height())
            .attr('width', legend.width())
            .attr('height', dropdownHeight)
            .attr('rx', 2)
            .attr('ry', 2)
            .attr('filter', nv.utils.dropShadow('legend_back_' + id, defs, {blur: 2}))
            .style('opacity', legendOpen * 0.9)
            .style('display', legendOpen ? 'inline' : 'none')
            .call(zoom);

          link
            .attr('transform', function(d, i) {
              var xpos = align === 'left' ? 0.5 : 0.5 + legend.width(),
                  ypos = margin.top + radius;
              return 'translate(' + xpos + ',' + ypos + ')';
            })
            .style('opacity', 1);

          mask
            .attr('clip-path', 'url(#nv-edge-clip-' + id + ')')
            .attr('transform', function(d, i) {
              var xpos = padding,
                  ypos = 0.5 + legend.height() + margin.top + radius;
              return 'translate(' + xpos + ',' + ypos + ')';
            });

          g
            .style('opacity', legendOpen)
            .style('display', legendOpen ? 'inline' : 'none')
            .attr('transform', function(d, i) {
              var xpos = direction === 'rtl' ? legend.width() - padding * 2 : 0;
              return 'translate(' + xpos + ',0)';
            })
            .call(zoom);

          series
            .attr('transform', function(d, i) {
              return 'translate(0,' + (i * (10 + radius * 2)) + ')';//TODO: why is this 10 hardcoded?
            });
          series
            .selectAll('circle')
              .attr('transform', '');
          series
            .selectAll('line')
              .attr('x1', 16)
              .attr('transform', 'translate(-8,0)')
              .style('stroke-dasharray', 'inherit');
          series
            .selectAll('text')
              .attr('text-anchor', 'start')
              .attr('transform', function(d, i) {
                var xpos = direction === 'rtl' ? -8 : 8;
                return 'translate(' + xpos + ',0)'; //TODO: why are these hardcoded?
            });

        }

      };

      //============================================================
      // Event Handling/Dispatching (in chart's scope)
      //------------------------------------------------------------

      function displayMenu() {
        back
          .style('opacity', legendOpen * 0.9)
          .style('display', legendOpen ? 'inline' : 'none');
        g
          .style('opacity', legendOpen)
          .style('display', legendOpen ? 'inline' : 'none');
        link
          .text(legendOpen === 1 ? legend.strings().close : legend.strings().open);
      }

      dispatch.on('toggleMenu', function(d) {
        d3.event.stopPropagation();
        legendOpen = 1 - legendOpen;
        displayMenu();
      });

      dispatch.on('closeMenu', function(d) {
        if (legendOpen === 1) {
          legendOpen = 0;
          displayMenu();
        }
      });

    });

    return legend;
  }


  //============================================================
  // Expose Public Variables
  //------------------------------------------------------------

  legend.dispatch = dispatch;

  legend.margin = function(_) {
    if (!arguments.length) { return margin; }
    margin.top    = typeof _.top    !== 'undefined' ? _.top    : margin.top;
    margin.right  = typeof _.right  !== 'undefined' ? _.right  : margin.right;
    margin.bottom = typeof _.bottom !== 'undefined' ? _.bottom : margin.bottom;
    margin.left   = typeof _.left   !== 'undefined' ? _.left   : margin.left;
    return legend;
  };

  legend.width = function(_) {
    if (!arguments.length) {
      return width;
    }
    width = Math.round(_);
    return legend;
  };

  legend.height = function(_) {
    if (!arguments.length) {
      return height;
    }
    height = Math.round(_);
    return legend;
  };

  legend.id = function(_) {
    if (!arguments.length) {
      return id;
    }
    id = _;
    return legend;
  };

  legend.key = function(_) {
    if (!arguments.length) {
      return getKey;
    }
    getKey = _;
    return legend;
  };

  legend.color = function(_) {
    if (!arguments.length) {
      return color;
    }
    color = nv.utils.getColor(_);
    return legend;
  };

  legend.classes = function(_) {
    if (!arguments.length) {
      return classes;
    }
    classes = _;
    return legend;
  };

  legend.align = function(_) {
    if (!arguments.length) {
      return align;
    }
    align = _;
    return legend;
  };

  legend.position = function(_) {
    if (!arguments.length) {
      return position;
    }
    position = _;
    return legend;
  };

  legend.showAll = function(_) {
    if (!arguments.length) { return showAll; }
    showAll = _;
    return legend;
  };

  legend.collapsed = function(_) {
    return collapsed;
  };

  legend.rowsCount = function(_) {
    if (!arguments.length) {
      return rowsCount;
    }
    rowsCount = _;
    return legend;
  };

  legend.lineSpacing = function(_) {
    if (!arguments.length) {
      return lineSpacing;
    }
    lineSpacing = _;
    return legend;
  };

  legend.strings = function(_) {
    if (!arguments.length) {
      return strings;
    }
    strings = _;
    return legend;
  };

  legend.equalColumns = function(_) {
    if (!arguments.length) {
      return equalColumns;
    }
    equalColumns = _;
    return legend;
  };

  legend.enabled = function(_) {
    if (!arguments.length) {
      return enabled;
    }
    enabled = _;
    return legend;
  };

  legend.direction = function(_) {
    if (!arguments.length) {
      return direction;
    }
    direction = _;
    return legend;
  };

  //============================================================


  return legend;
};
