export default interface DefineUser 
{
    username? : string,
    id?: string,
    friends?: DefineUser[],
    token?: string
}