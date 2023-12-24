
export interface DefinePost 
{
    posterName?: string,
    title?: string,
    id: string,
    isEdit: boolean,
    timestamp?: string,
    content?: string,
    tags?: string[]
};

export interface PostAPIData
{
    postC: number,
    posts: DefinePost[]
};
