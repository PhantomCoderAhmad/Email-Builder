import React, { useMemo, useState, useEffect  } from 'react';
import { renderToStaticMarkup } from '@usewaypoint/email-builder';
import { SaveOutlined } from '@mui/icons-material';
import { IconButton, Tooltip } from '@mui/material';
import { useDocument } from '../../../documents/editor/EditorContext';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-react';


export default function SaveTemplate() {
    const doc = useDocument();
    var templateId: string | null;
    function convertToHTML() {
        return renderToStaticMarkup(doc, { rootBlockId: 'root' });
    }

   function getTemplateId() {
            // Extract template ID from the URL
            const hash = window.location.hash; // Get the part after '#'
            const id = hash.split('/')[1]; // Assuming the format is #template/{id}
            console.log(id);
            if (id) {
              templateId = id; // Set the extracted ID to state
            }else{
              templateId = null;
            }
          } // Empty dependency array ensures this runs only once on component mount

    function handleSubmit() {
        var jsonData = doc;
        var htmlData = convertToHTML();
        getTemplateId();
        let templateName;
        if (!templateId) {
            templateName = prompt('Enter template name');
            if (templateName == null || templateName == "") {
                alert('Please enter a valid template name');
                return;
            }
            Inertia.post('/admin/email-builder/save-template', {
                templateId: templateId,
                htmlData: htmlData,
                templateName: templateName,
                jsonData: jsonData
            }, {
                onSuccess: () => {
                    alert('Template saved successfully');
                },
                onerror: () => {
                    alert('Failed to save template');
                }
            });
        }else{

            Inertia.post('/admin/email-builder/update-template', {
                templateId: templateId,
                htmlData: htmlData,
                templateName: templateName,
                jsonData: jsonData
            }, {
                preserveState: false,
                onSuccess: () => {
                    alert('Template updated successfully');
                },
                onerror: () => {
                    alert('Failed to Update template');
                }
            });
        }

    }

    return (
        <Tooltip title="Save Template">
            <IconButton onClick={handleSubmit}>
                <SaveOutlined fontSize="small" />
            </IconButton>
        </Tooltip>
    );
}
