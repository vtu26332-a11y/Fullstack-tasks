package com.jobportal.controller;

import com.jobportal.dto.RegistrationRequest;
import com.jobportal.entity.Role;
import com.jobportal.service.JobService;
import com.jobportal.service.CustomUserDetailsService;
import com.jobportal.service.UserService;
import jakarta.validation.Valid;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;

import java.security.Principal;

@Controller
public class AuthController {
    private final UserService userService;
    private final CustomUserDetailsService detailsService;
    private final JobService jobService;

    public AuthController(UserService userService, CustomUserDetailsService detailsService, JobService jobService) {
        this.userService = userService;
        this.detailsService = detailsService;
        this.jobService = jobService;
    }

    @GetMapping("/login")
    public String login() {
        return "login";
    }

    @GetMapping("/register")
    public String registerForm(Model model) {
        model.addAttribute("request", new RegistrationRequest());
        model.addAttribute("roles", new Role[]{Role.STUDENT, Role.EMPLOYER});
        return "register";
    }

    @PostMapping("/register")
    public String register(@Valid @ModelAttribute("request") RegistrationRequest request,
                           BindingResult result,
                           Model model) {
        if (result.hasErrors()) {
            model.addAttribute("roles", new Role[]{Role.STUDENT, Role.EMPLOYER});
            return "register";
        }
        userService.register(request);
        return "redirect:/login?registered";
    }

    @GetMapping("/dashboard")
    public String dashboardRedirect(Principal principal) {
        Role role = detailsService.roleOf(principal.getName());
        if (role == Role.ADMIN) return "redirect:/admin/dashboard";
        if (role == Role.EMPLOYER) return "redirect:/employer/dashboard";
        return "redirect:/student/dashboard";
    }

    @GetMapping("/")
    public String landing(org.springframework.ui.Model model) {
        // Show latest jobs on the public landing page
        model.addAttribute("featuredJobs", jobService.getAllJobs().stream().limit(6).toList());
        return "landing";
    }
}
