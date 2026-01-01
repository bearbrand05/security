public class Main {
    public static void main(String[] args) {
        String password = "secret";
        String hash = PasswordService.hashPassword(password);
        System.out.println("Password: " + password);
        System.out.println("Hash: " + hash);
        System.out.println("Verify correct: " + PasswordService.verifyPassword(password, hash));
        System.out.println("Verify wrong: " + PasswordService.verifyPassword("wrong", hash));
    }
}
