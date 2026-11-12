/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import './style.scss';
import transforms from './transforms';

function Edit() {
    const blockProps = useBlockProps();
    return (
        <div { ...blockProps }>
            <InnerBlocks />
        </div>
    );
}

// Must keep producing exactly this markup: saved poems are validated against it.
function Save() {
    const blockProps = useBlockProps.save();
    return (
        <div { ...blockProps } className="poem">
            <InnerBlocks.Content />
        </div>
    );
}

registerBlockType('create-block/poem-block', {
    title: 'Poem Block',
    icon: 'smiley',
    category: 'text',
    supports: {
        html: false,
        inserter: true,
        reusable: true,
    },
    edit: Edit,
    save: Save,
    transforms,
});

