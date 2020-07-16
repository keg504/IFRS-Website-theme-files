wp.blocks.registerBlockType('ifrs-website/gallery-block', {
    title: 'Gallery Block',
    icon: 'images-alt',
    category: 'layout',
    attributes: {
      content: {type: 'string'},
      color: {type: 'string'}
    },
    edit: function(props) {
      function updateContent(event) {
        props.setAttributes({content: event.target.value})
      }
  
      function updateColor(value) {
        props.setAttributes({color: value.hex})
      }
  
      return wp.element.createElement(
        "div",
        null,
        wp.element.createElement(
          "h3",
          null,
          "Gallery Block"
        ),
        wp.element.createElement("input", { type: "text", value: props.attributes.content, onChange: updateContent }),
        wp.element.createElement(wp.components.ColorPicker, { color: props.attributes.color, onChangeComplete: updateColor })
      );
    },
    save: function(props) {
      return null; /*wp.element.createElement(
        "h3",
        { style: { border: "5px solid " + props.attributes.color } },
        props.attributes.content
      );*/
    }
  })