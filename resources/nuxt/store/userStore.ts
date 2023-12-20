import { defineStore } from "pinia";
import DefineUser from "../types/user";
import Cookies from "js-cookie";


export interface DefineUsersStore
{
    data: DefineUser | null
}


export interface AuthJSONMsg
{
    message: string,
    payload: LoginJSONPayload | RegisterJSONPayload | null
}

export interface LoginJSONPayload
{
    accessToken : string,
    username : string,
    id : string
}

export interface RegisterJSONPayload
{
}


// Data store for users
export const useUserStore = defineStore({
    id: 'user-store',
    state: () : DefineUsersStore => ({
        data: {
            token: Cookies.get("accessToken")
        }
    }),
    actions: {
        async auth(email : string, password : string) : Promise<string | null>
        {
            try
            {
                let cookie : AuthJSONMsg = await $fetch("http://localhost:8000/api/auth/login", {
                    method: "POST",
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                let payload : LoginJSONPayload = (cookie.payload as LoginJSONPayload);
                this.$state.data = {
                    token: payload.accessToken,
                    id: payload.id,
                    friends: [],
                    username: payload.username
                }
                return payload.accessToken;
            }
            catch (error)
            {   
                return null;
            }            
        },
        async register(email: string, username: string, password: string) : Promise<boolean>
        {
            try
            {
                let cookie : AuthJSONMsg = await $fetch("http://localhost:8000/api/auth/register", {
                    method: "POST",
                    body: JSON.stringify({
                        name: username,
                        email: email,
                        password: password
                    })
                });

                if (cookie.message !== "Successfully created user!")
                {
                    return false;
                }

                return true;
            }
            catch (error)
            {
                return false;
            }
        }
    },
    getters: {
        user: (state) => state.data,
        isLoggedIn: (state) => state.data?.token
    }
});