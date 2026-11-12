import './style.scss';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import transforms from './transforms';

registerBlockType('create-block/poem-block', {
    title: 'Poem Block',
    icon: 'smiley',
    category: 'text',
    supports: {
        html: false,
        inserter: true,
        reusable: true,
    },
    edit: () => {
        const blockProps = useBlockProps();
        return (
            <div { ...blockProps }>
                <InnerBlocks />
            </div>
        );
    },
    save: () => {
        const blockProps = useBlockProps.save();
        return (
            <div { ...blockProps } className="poem">
                <InnerBlocks.Content />
            </div>
        );
    },
    transforms,
});

console.log('Poem Block registered successfully');
