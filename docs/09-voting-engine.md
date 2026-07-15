# 09 — Voting Engine

## Overview

Core voting engine — vote casting, encryption, anonymity enforcement, verification codes, and receipt generation.

## Execution Instructions

1. Create VoteService for all voting logic
2. Implement encryption and verification
3. Create vote controller methods
4. Run tests
5. Run `vendor/bin/pint --dirty --format agent`
6. Update `docs/PROGRESS.md`

## VoteService

**File:** `app/Services/VoteService.php`

```php
class VoteService
{
    /**
     * Cast a vote for a candidate in a position.
     * Creates both a Vote record (anonymous) and a VoteRecord (for double-vote prevention).
     */
    public function castVote(User $user, Election $election, Position $position, int $candidateId): Vote

    /**
     * Generate a unique 16-character alphanumeric verification code.
     */
    public function generateVerificationCode(): string

    /**
     * Generate a SHA-256 receipt hash from vote data.
     */
    public function generateReceiptHash(Election $election, Position $position, int $candidateId, string $verificationCode): string

    /**
     * Encrypt the vote choice for storage.
     * Uses AES-256-CBC with app key.
     */
    public function encryptChoice(int $candidateId, string $verificationCode): string

    /**
     * Verify a vote by verification code.
     * Returns vote details without revealing the choice to non-owner.
     */
    public function verifyVote(string $verificationCode): ?array

    /**
     * Check if user has voted for a specific position.
     */
    public function hasVotedForPosition(User $user, Position $position): bool

    /**
     * Check if user has voted in an election.
     */
    public function hasVotedInElection(User $user, Election $election): bool

    /**
     * Get vote history for a user.
     */
    public function getVoteHistory(User $user): Collection

    /**
     * Tally votes for a position.
     */
    public function tallyPosition(Position $position): Collection

    /**
     * Tally all votes for an election.
     */
    public function tallyElection(Election $election): array
}
```

## Vote Casting Process (Detailed)

```
1. Voter submits vote (candidate_id)
   ↓
2. Validate:
   - User is authenticated, verified, active
   - User role is voter
   - Election is active (status = active)
   - User is in election_voters for this election
   - User has NOT voted for this position (check vote_records)
   - Candidate is approved and belongs to this position
   ↓
3. Generate verification code:
   - 16-character alphanumeric (uppercase, no ambiguous chars like 0/O, 1/I)
   - Check uniqueness against existing codes
   ↓
4. Generate receipt hash:
   - SHA-256 of: election_id + position_id + candidate_id + verification_code + timestamp + app_key
   - This is cryptographically verifiable but does not reveal the voter
   ↓
5. Encrypt choice:
   - AES-256-CBC encrypt candidate_id using app key
   - Store as encrypted_choice in votes table
   ↓
6. Create Vote record (ANONYMOUS):
   - election_id, position_id, candidate_id, verification_code, receipt_hash, encrypted_choice, cast_at
   - NO user_id in this table
   ↓
7. Create VoteRecord (for double-vote prevention):
   - user_id, election_id, position_id, verification_code, receipt_hash, voted_at
   - This links user to the fact they voted, NOT to how they voted
   ↓
8. Audit log:
   - Log action: 'vote_cast'
   - Description: 'User voted in position {position_title} of election {election_title}'
   - Do NOT log candidate_id or verification_code
   ↓
9. Return Vote model with verification_code and receipt_hash
   ↓
10. Redirect to confirmation page
```

## Anonymity Guarantees

1. **votes table** has NO user_id column — cannot be joined to users
2. **vote_records table** has user_id but NO candidate_id — cannot be joined to votes to reveal choice
3. The link between vote and vote_record is the verification_code, which is only known to the voter
4. encrypted_choice uses AES-256-CBC with app key — even DB access doesn't reveal choices
5. Audit logs record that a vote was cast but not for whom

## Verification Flow

```
1. Voter enters verification code on verify-vote page
   ↓
2. System searches votes table by verification_code
   ↓
3. If found:
   - Show: election title, position title, receipt hash, cast_at timestamp
   - Show: "Your vote has been counted" confirmation
   - Do NOT show which candidate was voted for (to prevent vote buying/selling)
   ↓
4. If not found:
   - Show: "Invalid verification code" error
```

## Abstention

If election settings allow_abstain = true:
- Voter can choose to abstain instead of selecting a candidate
- A Vote record is still created with candidate_id = 0 (or a special abstain marker)
- VoteRecord is created to prevent double voting
- Verification code and receipt hash are still generated

## Tests

### VotingEngineTest

- `it can cast a vote`
- `it generates unique verification codes`
- `it generates receipt hashes`
- `it encrypts vote choices`
- `it creates anonymous vote record without user_id`
- `it creates vote record for double-vote prevention`
- `it prevents double voting`
- `it prevents voting on non-active election`
- `it prevents voting for non-approved candidate`
- `it prevents voting for candidate in wrong position`
- `it can verify vote with valid code`
- `it cannot verify vote with invalid code`
- `it does not reveal candidate choice in verification`
- `it can abstain from a position`
- `it logs vote in audit trail without revealing choice`
- `it can tally votes for a position`
- `it can tally votes for an election`
- `it calculates vote percentages correctly`
- `it handles ties in results`
- `vote records cannot be linked to vote choices via database`

## Do NOT proceed until:
- [ ] VoteService with all methods
- [ ] Vote casting flow fully implemented
- [ ] Anonymity guarantees enforced
- [ ] Verification flow working
- [ ] Abstention working
- [ ] All 20 Pest tests pass
- [ ] Pint formatting passes
