
export interface DefineReminder 
{
    title?: string,
    id: string,
    isEdit: boolean,
    timestamp?: string,
    content?: string,
    tags?: string[], 
    collapseContent: boolean
};