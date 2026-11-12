/**
 * WordPress dependencies
 */
import { createBlock } from '@wordpress/blocks';

const transforms = {
    from: [
        {
            type: 'block',
            isMultiBlock: true,
            blocks: [ '*' ],
            __experimentalConvert( blocks ) {
                // Clone the Blocks to be grouped into the Poem Block
                const poemInnerBlocks = blocks.map( ( block ) => {
                    return createBlock(
                        block.name,
                        block.attributes,
                        block.innerBlocks
                    );
                } );

                // Create the Poem Block with the inner blocks
                return createBlock(
                    'create-block/poem-block',
                    {},
                    poemInnerBlocks
                );
            },
        },
    ],
};

export default transforms;
