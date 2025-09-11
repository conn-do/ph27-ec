import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AuthLayout from "@/layouts/auth-layout";
import { useForm } from "@inertiajs/react";

type RegisterForm = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    state: string;
}

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm<Required<RegisterForm>>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        state: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/register', {
            onFinish: () => reset('password'),
        });
    };

    return (
        <AuthLayout title="Register" description="Create your account">
            <form onSubmit={submit} className="space-y-4">
                <div>
                    <Label htmlFor="name">Name</Label>
                    <Input
                        id="name"
                        type="text"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />
                    {errors.name && <p className="text-red-500 text-sm">{errors.name}</p>}
                </div>

                <div>
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />
                    {errors.email && <p className="text-red-500 text-sm">{errors.email}</p>}
                </div>

                <div>
                    <Label htmlFor="state">State</Label>
                    <Input
                        id="state"
                        type="text"
                        value={data.state}
                        onChange={(e) => setData('state', e.target.value)}
                    />
                    {errors.state && <p className="text-red-500 text-sm">{errors.state}</p>}
                </div>

                <div>
                    <Label htmlFor="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />
                    {errors.password && <p className="text-red-500 text-sm">{errors.password}</p>}
                </div>

                <div>
                    <Label htmlFor="password_confirmation">Confirm Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        value={data.password_confirmation || ''}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        required
                    />
                </div>

                <Button type="submit" disabled={processing} className="w-full">
                    {processing ? 'Creating Account...' : 'Register'}
                </Button>
            </form>
        </AuthLayout>
    );
}